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

class contactmodule {
	function name() { return "Contact Form"; }
	function description() { return "Presents a form to the web viewer, which they can use to contact users."; }
	function author() { return "James Hunt"; }
	
	function hasSources() { return true; }
	function hasContent() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = '') {
		pathos_lang_loadDictionary('modules','contactmodule');
		return array(
			'administrate'=>TR_CONTACTMODULE_PERM_ADMIN,
			'configure'=>TR_CONTACTMODULE_PERM_ADMIN,
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