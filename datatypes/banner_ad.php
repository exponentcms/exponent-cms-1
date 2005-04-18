<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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
# $Id$
##################################################

/**
 * Banner Ad Contact
 *
 * Provides a form and an updater for editting and saving
 * an address book contact.
 *
 * @author James Hunt
 * @copyright 2004 James Hunt and the OIC Group, Inc.
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
		if (!defined("SYS_FORMS")) require_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
	
		$form = new form();
		if (!isset($object->id)) {
			$object->name = "";
			$object->affiliate_id = 0;
			$object->url = "http://";
		} else {
			$form->meta("id",$object->id);
			
			global $db;
			$file = $db->selectObject("file","id=".$object->file_id);
			$form->register(uniqid(""),"",new htmlcontrol("<img src='".$file->directory."/".$file->filename."'/>"));
		}
		
		pathos_lang_loadDictionary('standard','core');
		pathos_lang_loadDictionary('modules','bannermodule');
		
		$affiliates = bannermodule::listAffiliates();
		
		$form->register('name',TR_BANNERMODULE_INTERNALNAME,new textcontrol($object->name));
		$submit = new buttongroupcontrol(TR_CORE_SAVE,'',TR_CORE_CANCEL);
		if (count($affiliates)) {
			$form->register('affiliate_id',TR_BANNERMODULE_AFFILIATE, new dropdowncontrol($object->affiliate_id,$affiliates));
		} else {
			$form->registerBefore('name',null,'',new htmlcontrol('<div class="error">'.TR_BANNERMODULE_NOAFFILIATES.'</div>'));
			$submit->disabled = 1;
		}
		$form->register('url',TR_BANNERMODULE_DESTURL,new texteditorcontrol($object->url,2,40));
		
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