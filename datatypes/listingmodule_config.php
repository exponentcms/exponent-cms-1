<?php

##################################################
#
# Copyright (c) 2004-2011 OIC Group, Inc.
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# Exponent is distributed in the hope that it
# will be useful, but WITHOUT ANY WARRANTY;
# without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR
# PURPOSE.  See the GNU General Public License
# for more details.
#
# You should have received a copy of the GNU
# General Public License along with Exponent; if
# not, write to:
#
# Free Software Foundation, Inc.,
# 59 Temple Place,
# Suite 330,
# Boston, MA 02111-1307  USA
#
##################################################

class listingmodule_config {
	function form($object) {
		$i18n = exponent_lang_loadFile('datatypes/listingmodule_config.php');

		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();

		$form = new form();
		if (!isset($object->id)) {
			$object->enable_categories = 0;
			$object->description = "";
			$object->sort = 'asc_name';
			$object->items_perpage = 10;
		} else {
			switch ($object->orderhow) {
				case 0: // ascending
					$object->sort = 'asc_'.$object->orderby;
					break;
				case 1: // descending
					$object->sort = 'desc_'.$object->orderby;
					break;
				case 2: // rank
					$object->sort = 'rank_';
					break;
				case 3: // random
					$object->sort = 'random_';
					break;
				default:
					$object->sort = 'asc_name';
					break;
			}
			$form->meta('id',$object->id);
		}

		$order_options = array(
			'asc_name'=>$i18n['sort_name_asc'],
			'desc_name'=>$i18n['sort_name_desc'],
			'rank_'=>$i18n['sort_rank'],
			'random_'=>$i18n['sort_random']
		);

		$form->register(null,'', new htmlcontrol('<h3>'.$i18n['categories'].'</h3><hr size="1" />'));	
		$form->register('enable_categories',$i18n['enable_categories'],new checkboxcontrol($object->enable_categories,true));
		$form->register('orderby',$i18n['sort_entries'],new dropdowncontrol($object->sort,$order_options));
		$form->register('items_perpage',$i18n['items_perpage'],new textcontrol($object->items_perpage,5));
		$form->register('description',$i18n['list_description'], new htmleditorcontrol($object->description));
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		return $form;
	}

	function update($values,$object) {

		$object->items_perpage = $values['items_perpage'];
		$object->enable_categories = empty($values['enable_categories']) ? 0 : 1;
		$object->description = preg_replace("/[\n\r]/","",$values['description']);

		$toks = explode('_',$values['orderby']);
		switch ($toks[0]) {
			case 'asc':
				$object->orderhow = 0;
				break;
			case 'desc':
				$object->orderhow = 1;
				break;
			case 'rank':
				$object->orderhow = 2;
				break;
			case 'random':
				$object->orderhow = 3;
				break;
		}
		$object->orderby = $toks[1];
		$object->recalc = 1;
		return $object;
	}
}

?>
