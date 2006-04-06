<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Written and Designed by James Hunt
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################

/**
 * Banner Ad Contact
 *
 * Provides a form and an updater for editting and saving
 * an address book contact.
 *
 * @author James Hunt
 * @copyright 2004-2006 OIC Group, Inc.
 * @version 0.95
 *
 * @package Modules
 * @subpackage BannerManager
 */

/**
 * Banner Ad class
 *
 * @package Modules
 * @subpackage BannerManager
 */
class banner_ad {
	function form($object) {
		if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
	
		$form = new form();
		if (!isset($object->id)) {
			$object->name = '';
			$object->affiliate_id = 0;
			$object->url = 'http://';
		} else {
			$form->meta('id',$object->id);
			
			global $db;
			$file = $db->selectObject('file','id='.$object->file_id);
			$form->register(uniqid(''),'',new htmlcontrol('<img src="'.$file->directory.'/'.$file->filename.'"/>'));
		}
		
		$i18n = exponent_lang_loadFile('datatypes/banner_ad.php');
		
		$affiliates = bannermodule::listAffiliates();
		
		$form->register('name',$i18n['name'],new textcontrol($object->name));
		$submit = new buttongroupcontrol($i18n['save'],'',$i18n['cancel']);
		if (count($affiliates)) {
			$form->register('affiliate_id',$i18n['affiliate_id'], new dropdowncontrol($object->affiliate_id,$affiliates));
		} else {
			$form->registerBefore('name',null,'',new htmlcontrol('<div class="error">'.$i18n['no_affiliates'].'</div>'));
			$submit->disabled = 1;
		}
		$form->register('url',$i18n['url'],new texteditorcontrol($object->url,2,40));
		
		$form->register('submit','',$submit);
		
		return $form;
	}
	
	function update($values,$object) {
		$object->name = $values['name'];
		$object->affiliate_id = $values['affiliate_id'];
		$object->url = $values['url'];
		return $object;
	}
}

?>