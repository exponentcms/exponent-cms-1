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
# $Id: class.php,v 1.8 2005/07/01 05:19:56 filetreefrog Exp $
##################################################

class headlinemodule {
	function name() { return 'Headline'; }
	function description() { return 'All about the headlines'; }
	function author() { return 'Phillip Ball'; }
	
	function hasSources() { return true; }
	function hasContent() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = '') {
		return array(
			'administrate'=>'Administrate Headline',
			'edit'=>'Edit Headline',
		);
	}
		
	function show($view,$loc = null, $title = '') {
		global $db;
		
		$template = new template('headlinemodule',$view,$loc);
		
		$listings = $db->selectObject('headline',"location_data='".serialize($loc)."'");

		$template->register_permissions(array('administrate','edit'),$loc);
		
		$template->assign('headline', $listings);

		$template->output();
	}
	
	function deleteIn($loc) {
		// IMPLEMENTME:deleteIn for the headline module
	}
	
	function copyContent($oloc,$nloc) {
		// IMPLEMENTME:copyContent for the headline module
	}

	// function searchName() {
	// 	return 'Listed Elements';
	// }
	// 
	// function spiderContent($item = null) {
	// 	global $db;
	// 	
	// 	if (!defined('SYS_SEARCH')) require_once(BASE.'subsystems/search.php');
	// 	
	// 	$search = null;
	// 	$search->category = 'Listings';
	// 	$search->ref_module = 'listingmodule';
	// 	$search->ref_type = 'listing';
	// 	
	// 	if ($item) {
	// 		$db->delete('search',"ref_module='listingmodule' AND ref_type='listing' AND original_id=" . $item->id);
	// 		$search->original_id = $item->id;
	// 		$search->title = ' ' . $item->name . ' ';
	// 		$search->view_link = 'index.php?module=listingmodule&action=view_listing&id='.$item->id;
	// 		$search->body = ' ' . exponent_search_removeHTML($item->body) . ' ';
	// 		$search->location_data = $item->location_data;
	// 		$db->insertObject($search,'search');
	// 	} else {
	// 		$db->delete('search',"ref_module='listingmodule' AND ref_type='listing'");
	// 		foreach ($db->selectObjects('listing') as $item) {
	// 			$search->original_id = $item->id;
	// 			$search->title = ' ' . $item->name . ' ';
	// 			$search->view_link = 'index.php?module=listingmodule&action=view_listing&id='.$item->id;
	// 			$search->body = ' ' . exponent_search_removeHTML($item->body) . ' ';
	// 			$search->location_data = $item->location_data;
	// 			$db->insertObject($search,'search');
	// 		}
	// 	}
	// 	
	// 	return true;
	// }
}

?>
