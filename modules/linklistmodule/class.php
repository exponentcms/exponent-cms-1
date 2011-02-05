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
# $Id: class.php,v 1.4 2005/05/09 05:55:13 filetreefrog Exp $
##################################################

class linklistmodule {
	function name() { return "Link List"; }
	function description() { return "Manage a set of links."; }
	function author() { return "OIC Group, Inc."; }

	function hasSources() { return true; }
	function hasContent() { return true; }
	function hasViews() { return true; }

	function supportsWorkflow() { return false; }

	function permissions($internal = "") {
		return array(
			'administrate'=>'Administrate',
			'configure'=>'Configure',
			'create'=>'Create Links',
			'edit'=>'Edit Links',
			'delete'=>'Delete Links'
		);
	}

	function show($view,$loc = null, $title = "") {
		global $db;

		$config = $db->selectObject('linklistmodule_config',"location_data='".serialize($loc)."'");
		if ($config == null) {
			$config->orderby = 'name';
			$config->orderhow = 0; // Ascending
		}

		if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');

		$links = $db->selectObjects('linklist_link',"location_data='".serialize($loc)."'");

		switch ($config->orderhow) {
			case 0:
				usort($links,'exponent_sorting_byNameAscending');
				break;
			case 1:
				usort($links,'exponent_sorting_byNameDescending');
				break;
			case 2:
				//sort the listings by their rank
				usort($links, 'exponent_sorting_byRankAscending');
				break;
			case 3:
				shuffle($links);
				break;
		}

		$template = new template('linklistmodule',$view,$loc);
		$template->assign('orderhow', $config->orderhow);
		$template->assign('links',$links);
		$template->assign('moduletitle',$title);
		$template->register_permissions(array('administrate','configure','create','edit','delete'),$loc);
		$template->output();
	}

	function deleteIn($loc) {
		global $db;
		$db->delete('linklist_link',"location_data='".serialize($loc)."'");
	}

	function copyContent($oloc,$nloc) {
		foreach ($db->selectObjects('linklist_link',"location_data='".serialize($oloc)."'") as $l) {
			$l->location_data = serialize($nloc);
			$db->insertObject($l,'linklist_link');
		}
	}

	function searchName() {
		return 'Link Lists';
	}

	function spiderContent($item = null) {
		global $db;

		if (!defined('SYS_SEARCH')) require_once(BASE.'subsystems/search.php');

		$search = null;
		$search->category = 'Links';
		$search->ref_module = 'linklistmodule';
		$search->ref_type = 'linklist_link';
		$search->view_link = '';

		if ($item) {
			$db->delete('search',"ref_module='linklistmodule' AND ref_type='linklist_link' AND original_id=" . $item->id);
			$search->original_id = $item->id;
			$search->title = ' ' . $item->name . ' ';
			$search->body = ' ' . exponent_search_removeHTML($item->description) . ' ';
			$search->location_data = $item->location_data;
			$db->insertObject($search,'search');
		} else {
			$db->delete('search',"ref_module='linklistmodule' AND ref_type='linklist_link'");
			foreach ($db->selectObjects('linklist_link') as $item) {
				$search->original_id = $item->id;
				$search->title = ' ' . $item->name . ' ';
				$search->body = ' ' . exponent_search_removeHTML($item->description) . ' ';
				$search->location_data = $item->location_data;
				$db->insertObject($search,'search');
			}
		}

		return true;
	}
}

?>
