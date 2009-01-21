<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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
# $Id: listingmodule_config.php,v 1.3 2005/04/25 19:02:16 filetreefrog Exp $
##################################################

class listingmodule_config {
	function form($object) {
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();

		$form = new form();
		if (!isset($object->id)) {
			$object->sort = 'asc_name';
			$object->items_perpage = 20;
		} else {
			switch ($object->orderhow) {
				case 0: // ascending
					$object->sort = 'asc_'.$object->orderby;
					break;
				case 1: // descending
					$object->sort = 'desc_'.$object->orderby;
					break;
				case 2: // rank
					$object->sort = 'desc_'.$object->orderby;
					break;
				case 3: // random
					$object->sort = 'rank_';
					break;
				default:
					$object->sort = 'asc_name';
					break;
			}
			$form->meta('id',$object->id);
		}

		$order_options = array(
			'asc_name'=>'Alphabetical By Name',
			'desc_name'=>'Reverse Alphabetical By Name',
			'rank_'=>'By Specific Position',
			'random_'=>'Randomly'
		);

		$form->register('orderby','Sorting',new dropdowncontrol($object->sort,$order_options));
		$form->register('items_perpage','Items per page: ',new textcontrol($object->items_perpage,5));
		$form->register('submit','',new buttongroupcontrol('Save','','Cancel'));
		return $form;
	}

	function update($values,$object) {
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
		$object->items_perpage = $values['items_perpage'];

		return $object;
	}
}

?>