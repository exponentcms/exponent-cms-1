<?php

##################################################
#
# Copyright 2004 James Hunt and OIC Group, Inc.
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
 * Address Book Module
 *
 * Manages a set of contacts.
 * @author James Hunt
 * @copyright 2004 James Hunt and the OIC Group, Inc.
 * @version 0.95
 *
 * @package Modules
 * @subpackage AddressBook
 */
/**
 * Module Class
 *
 * Primary entrypoit to the module.
 *
 * @package Modules
 * @subpackage AddressBook
 */
class addressbookmodule {
	function name() { return "Address Book"; }
	function description() { return "Manages a list of contacts, storing information like names, addresses, emails and phone numbers."; }
	function author() { return "James Hunt"; }
	
	function hasSources() { return true; }
	function hasContent() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = "") {
		if ($internal == "") {
			return array(
				"administrate"=>"Administrate",
		//		"configure"=>"Configure",
				"post"=>"Create Contacts",
				"edit"=>"Edit Contacts",
				"delete"=>"Delete Contacts",
				"reference"=>"Reference Contacts",
				"copy"=>"Copy Contacts"
			);
		} else {
			return array(
				"administrate"=>"Administrate",
				"edit"=>"Edit Contact",
				"delete"=>"Delete Contact",
				"reference"=>"Reference Contact",
				"copy"=>"Copy Contact"
			);
		}
	}

	function getLocationHierarchy($loc) {
		if ($loc->int == "") return array($loc);
		else return array($loc,pathos_core_makeLocation($loc->mod,$loc->src));
	}
	
	function show($view,$loc = null,$title = "") {
		global $db;
		$contacts = addressbookmodule::getContacts($loc);
		
		$template = new template("addressbookmodule",$view,$loc);
		
		$template->assign("contacts",$contacts);
		$template->assign("moduletitle",$title);
		$template->register_permissions(
			array("administrate","post","edit","delete","reference","copy"),
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
			$db->insertObject($entry,"addressbook_contact");
		}
		
	}
	
	function getContacts($location) {
		global $db;
		$contacts = array();
		foreach ($db->selectObjects("addressbook_contact","location_data='".serialize($location)."'") as $c) {
			if ($c->copy_id != 0) {
				$contacts[$c->id] = $db->selectObject("addressbook_contact","id=".$c->copy_id);
				$contacts[$c->id]->copy_id = $c->copy_id;
				$contacts[$c->id]->id = 0;
			} else {
				$contacts[$c->id] = $c;
			}
			
			$iloc = pathos_core_makeLocation($location->mod,$location->src,$c->id);
			$contacts[$c->id]->permissions = array(
				"administrate"=>pathos_permissions_check("administrate",$iloc),
				"edit"=>pathos_permissions_check("edit",$iloc),
				"delete"=>pathos_permissions_check("delete",$iloc),
				"reference"=>pathos_permissions_check("reference",$iloc),
				"copy"=>pathos_permissions_check("copy",$iloc)
			);
		}
		return $contacts;
	}
}

?>