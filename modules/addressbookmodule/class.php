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

class addressbookmodule {
	function name() { return "Address Book"; }
	function description() { return "Manages a list of contacts, storing information like names, addresses, emails and phone numbers."; }
	function author() { return "James Hunt"; }
	
	function hasSources() { return true; }
	function hasContent() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = '') {
		pathos_lang_loadDictionary('modules','addressbookmodule');
		if ($internal == '') {
			return array(
				'administrate'=>TR_ADDRESSBOOKMODULE_PERM_ADMIN,
				'post'=>TR_ADDRESSBOOKMODULE_PERM_POST,
				'edit'=>TR_ADDRESSBOOKMODULE_PERM_EDIT,
				'delete'=>TR_ADDRESSBOOKMODULE_PERM_DELETE
			);
		} else {
			return array(
				'administrate'=>TR_ADDRESSBOOKMODULE_PERM_ADMIN,
				'edit'=>TR_ADDRESSBOOKMODULE_PERM_EDITONE,
				'delete'=>TR_ADDRESSBOOKMODULE_PERM_DELETEONE
			);
		}
	}

	function getLocationHierarchy($loc) {
		if ($loc->int == '') return array($loc);
		else return array($loc,pathos_core_makeLocation($loc->mod,$loc->src));
	}
	
	function show($view,$loc = null,$title = '') {
		global $db;
		$contacts = addressbookmodule::getContacts($loc);
		
		$template = new template('addressbookmodule',$view,$loc);
		
		$template->assign('contacts',$contacts);
		$template->assign('moduletitle',$title);
		$template->register_permissions(
			array('administrate','post','edit','delete'),
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
	}
}

?>