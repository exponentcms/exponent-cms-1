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

class addressbookmodule {
	function name() { return pathos_lang_loadKey('modules/addressbookmodule/class.php','module_name'); }
	function description() { return pathos_lang_loadKey('modules/addressbookmodule/class.php','module_description'); }
	function author() { return 'James Hunt'; }
	
	function hasSources() { return true; }
	function hasContent() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = '') {
		$i18n = pathos_lang_loadFile('modules/addressbookmodule/class.php');
		
		if ($internal == '') {
			return array(
				'administrate'=>$i18n['perm_administrate'],
				'configure'=>$i18n['perm_configure'],
				'post'=>$i18n['perm_post'],
				'edit'=>$i18n['perm_edit'],
				'delete'=>$i18n['perm_delete'],
			);
		} else {
			return array(
				'administrate'=>$i18n['perm_administrate'],
				'edit'=>$i18n['perm_edit_one'],
				'delete'=>$i18n['perm_delete_one']
			);
		}
	}

	function getLocationHierarchy($loc) {
		if ($loc->int == '') {
			return array($loc);
		}
		else {
			return array($loc,pathos_core_makeLocation($loc->mod,$loc->src));
		}
	}
	
	function show($view,$loc = null,$title = '') {
		global $db;
		
		$config = $db->selectObject('addressbookmodule_config',"location_data='".serialize($loc)."'");
		if (!$config) {
			$config->sort_type = 'lastname_asc';
		}
		$contacts = addressbookmodule::getContacts($loc);
		// Sorting
		if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
		if (!function_exists('pathos_sorting_byFirstNameAscending')) {
			function pathos_sorting_byFirstNameAscending($a,$b) {
				return strnatcmp($a->firstname ,$b->firstname);
			}
		}
		if (!function_exists('pathos_sorting_byFirstNameDescending')) {
			function pathos_sorting_byFirstNameDescending($a,$b) {
				return -1*strnatcmp($a->firstname ,$b->firstname);
			}
		}
		if (!function_exists('pathos_sorting_byLastNameAscending')) {
			function pathos_sorting_byLastNameAscending($a,$b) {
				return strnatcmp($a->lastname ,$b->lastname);
			}
		}
		if (!function_exists('pathos_sorting_byLastNameDescending')) {
			function pathos_sorting_byLastNameDescending($a,$b) {
				return -1*strnatcmp($a->lastname ,$b->lastname);
			}
		}
		
		switch ($config->sort_type) {
			case 'lastname_asc':
				usort($contacts,'pathos_sorting_byLastNameAscending');
				break;
			case 'lastname_desc':
				usort($contacts,'pathos_sorting_byLastNameDescending');
				break;
			case 'firstname_asc':
				usort($contacts,'pathos_sorting_byFirstNameAscending');
				break;
			case 'firstname_desc':
				usort($contacts,'pathos_sorting_byFirstNameDescending');
				break;
		}
		
		$template = new template('addressbookmodule',$view,$loc);
		
		$template->assign('contacts',$contacts);
		$template->assign('moduletitle',$title);
		$template->register_permissions(
			array('administrate','configure','post','edit','delete'),
			$loc
		);
		
		$template->output();
	}
	
	function deleteIn($loc) {
		global $db;
		$db->delete("addressbook_contact","location_data='".serialize($loc)."'");
	}
	
	function copyContent($oloc,$nloc) {
		global $db;
		foreach ($db->selectObjects("addressbook_contact","location_data='".serialize($oloc)."'") as $entry) {
			$entry->location_data = serialize($nloc);
			unset($entry->id);
			$db->insertObject($entry,'addressbook_contact');
		}
		
	}
	
	function getContacts($location) {
		global $db;
		$contacts = array();
		foreach ($db->selectObjects("addressbook_contact","location_data='".serialize($location)."'") as $c) {
			$contacts[$c->id] = $c;
			
			$iloc = pathos_core_makeLocation($location->mod,$location->src,$c->id);
			$contacts[$c->id]->permissions = array(
				'administrate'=>pathos_permissions_check('administrate',$iloc),
				'edit'=>pathos_permissions_check('edit',$iloc),
				'delete'=>pathos_permissions_check('delete',$iloc)
			);
		}
		return $contacts;
	}
	
	function spiderContent($item = null) {
		// Do nothing for address book
		return false;
	}
}

?>