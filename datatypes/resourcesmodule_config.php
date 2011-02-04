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
# $Id: articlemodule_config.php,v 1.3 2005/04/25 19:02:15 filetreefrog Exp $
##################################################

class resourcesmodule_config {
	function form($object) {
		$i18n = exponent_lang_loadFile('datatypes/resourcesmodule_config.php');
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->enable_categories = 0;
			$object->sort = 'desc_posted';
			$object->allow_anon_downloads = 1;
			$object->description = "";
			$object->enable_rss = 0;
			$object->feed_title = "";
			$object->feed_desc = "";
			$object->rss_limit = 25;
			$object->rss_cachetime = 1440;
			$object->require_agreement = 0;
			$object->agreement_body = "";
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
					$object->sort = 'desc_posted';
					break;
			}
			$form->meta('id',$object->id);
		}
		
		$order_options = array(
			'asc_posted'=>$i18n['sort_dateposted_asc'],
			'desc_posted'=>$i18n['sort_dateposted_desc'],
			'asc_edited'=>$i18n['sort_dateedited_asc'],
			'desc_edited'=>$i18n['sort_dateedited_desc'],
			'asc_downloads'=>$i18n['sort_downloads_asc'],
			'desc_downloads'=>$i18n['sort_downloads_desc'],			
			'asc_name'=>$i18n['sort_name_asc'],
			'desc_name'=>$i18n['sort_name_desc'],
			'rank_'=>$i18n['sort_rank'],
			'random_'=>$i18n['sort_random']
		);
		
		$form->register(null,'', new htmlcontrol('<h3>'.$i18n['categories'].'</h3><hr size="1" />'));	
		$form->register('enable_categories',$i18n['enable_categories'],new checkboxcontrol($object->enable_categories,true));		
		$form->register('orderby',$i18n['sort_entries'],new dropdowncontrol($object->sort,$order_options));
		$form->register('description',$i18n['list_description'], new htmleditorcontrol($object->description));
		$form->register('allow_anon_downloads',$i18n['allows_anonymous_downloads'],new checkboxcontrol($object->allow_anon_downloads,true));
		$form->register(null,'',new htmlcontrol('<h3>'.$i18n['confidentiality_agreement'].'</h3><hr size="1" />'));
		$form->register('require_agreement',$i18n['require_confidentiality_agreement'],new checkboxcontrol($object->require_agreement,true));
		$form->register('agreement_body',$i18n['enter_confidentiality_agreement'],new htmleditorcontrol($object->agreement_body));
		$form->register(null,'',new htmlcontrol('<h3>'.$i18n['podcasting_configuration'].'</h3><hr size="1" />'));
		$form->register('enable_rss',$i18n['enable_rss'], new checkboxcontrol($object->enable_rss,true));
		$form->register('feed_title',$i18n['podcast_title'],new textcontrol($object->feed_title,35,false,75));
		$form->register('feed_desc',$i18n['podcast_description'],new texteditorcontrol($object->feed_desc));
		$form->register('rss_cachetime', $i18n['rss_cachetime'], new textcontrol($object->rss_cachetime));
		$form->register('rss_limit', $i18n['rss_limit'], new textcontrol($object->rss_limit));
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		return $form;
	}
	
	function update($values,$object) {
		$object->enable_categories = empty($values['enable_categories']) ? 0 : 1;
		$object->description = preg_replace("/[\n\r]/","",$values['description']);
		$object->allow_anon_downloads = isset($values['allow_anon_downloads']);
		$object->require_agreement = isset($values['require_agreement']);
		$object->agreement_body = $values['agreement_body'];
		$object->enable_rss = isset($values['enable_rss']);
		$object->feed_title = $values['feed_title'];
		$object->feed_desc = $values['feed_desc'];
		$object->rss_cachetime = $values['rss_cachetime'];
		$object->rss_limit = $values['rss_limit'];
		
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
		
		return $object;
	}
}

?>
