<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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

class contactmodule {
	function name() { return pathos_lang_loadKey('modules/contactmodule/class.php','module_name'); }
	function description() { return pathos_lang_loadKey('modules/contactmodule/class.php','module_description'); }
	function author() { return 'James Hunt'; }
	
	function hasSources() { return true; }
	function hasContent() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = '') {
		$i18n = pathos_lang_loadFile('modules/contactmodule/class.php');
		
		return array(
			'administrate'=>$i18n['perm_administrate'],
			'configure'=>$i18n['perm_configure'],
		);
	}
	
	function show($view,$loc = null,$title = '') {
		global $db;
		
		$contacts = $db->selectObjects('contact_contact',"location_data='".serialize($loc)."'");
		
		$t = new template('contactmodule','_standard',$loc);
		$t->register_permissions(array(
			'administrate','configure'),
			$loc);
		$t->output();
		
		$template = new template('contactmodule',$view,$loc);
		$template->assign('contacts',$contacts);
		$template->assign('loc',$loc);
		$template->assign('numContacts',count($contacts));
		$template->assign('moduletitle',$title);
		$template->register_permissions(array(
			'administrate','configure'),
			$loc);
		
		$template->output();
	}
	
	function deleteIn($loc) {
		global $db;
		$db->delete('contact_contact',"location_data='".serialize($loc)."'");
	}
	
	function copyContent($oloc,$nloc) {
		global $db;
		foreach ($db->selectObjects('contact_contact',"location_data='".serialize($oloc)."'") as $contact) {
			unset($contact->id);
			$contact->location_data = serialize($nloc);
			$db->insertObject($contact,'contact_contact');
		}
	}
	
	function spiderContent($item = null) {
		// Do nothing, no content
		return false;
	}
}

?>