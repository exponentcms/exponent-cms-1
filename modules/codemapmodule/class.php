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
# $Id: class.php,v 1.7 2005/05/09 05:47:22 filetreefrog Exp $
##################################################

class codemapmodule {
	function name() { return "Software Roadmap"; }
	function description() { return "Store and analyze milestones in a software project."; }
	function author() { return "OIC Group, Inc."; }
	
	function hasSources() { return true; }
	function hasContent() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = "") {
		return array(
			"administrate"=>"Administrate",
			"configure"=>"Configure",
			"manage_miles"=>"Manage Milestones",
			"manage_steps"=>"Manage Stepstones"
		);
	}
	
	function show($view,$loc = null, $title = "") {
		global $db;
		$milestones = $db->selectObjects("codemap_milestone","location_data='".serialize($loc)."'");
		if (!defined("SYS_SORTING")) require_once(BASE."subsystems/sorting.php");
		uasort($milestones,"exponent_sorting_byRankAscending");
		
		$stepstones = array();
		foreach ($milestones as $m) {
			$tmp = $db->selectObjects("codemap_stepstone","milestone_id=".$m->id." AND location_data='".serialize($loc)."'");
			usort($tmp,"exponent_sorting_byNameAscending");
			$stepstones = array_merge($stepstones,$tmp);
		}
		#$stepstones = $db->selectObjects("codemap_stepstone","location_data='".serialize($loc)."'");
		
		
		$template = new template("codemapmodule",$view,$loc);
		$template->assign("milestones",$milestones);
		$template->assign("stepstones",$stepstones);
		$template->assign("moduletitle",$title);
		$template->register_permissions(
			array("administrate",/*"config",*/"manage_steps","manage_miles"),
			$loc);
		$template->output();
	}
	
	function deleteIn($loc) {
		global $db;
		$db->delete("codemap_milestone","location_data='".serialize($loc)."'");
		$db->delete("codemap_stepstone","location_data='".serialize($loc)."'");
	}
	
	function copyContent($oloc,$nloc) {
		global $db;
		$nloc = serialize($nloc);
		foreach ($db->selectObjects('codemap_milestone',"location_data='".serialize($oloc)."'") as $mile) {
			$old_mile_id = $mile->id;
			unset($mile->id);
			$mile->location_data = $nloc;
			$mile->id = $db->insertObject($mile,'codemap_milestone');
			foreach ($db->selectObjects('codemap_stepstone','milestone_id='.$old_mile_id) as $stone) {
				$stone->milestone_id = $mile->id;
				$stone->location_data = $nloc;
				unset($stone->id);
				$db->insertObject($stone,'codemap_stepstone');
			}
		}
	}

	function searchName() {
		return "Development Roadmap";
	}	
	function spiderContent($item = null) {
		global $db;
		
		if (!defined('SYS_SEARCH')) require_once(BASE.'subsystems/search.php');
		
		$search = null;
		$search->category = 'Software Roadmap Entry';
		$search->ref_module = 'codemapmodule';
		$search->ref_type = 'codemap_stepstone';
		
		if ($item) {
			$db->delete('search',"ref_module='codemapmodule' AND ref_type='codemap_stepstone' AND original_id=" . $item->id);
			$search->original_id = $item->id;
			$search->title = ' ' . $item->name . ' ';
			$search->view_link = 'index.php?module=codemapmodule&action=stepstone_view&id='.$item->id;
			$search->body = ' ' . exponent_search_removeHTML($item->description) . ' ';
			$search->location_data = $item->location_data;
			$db->insertObject($search,'search');
		} else {
			$db->delete('search',"ref_module='codemapmodule' AND ref_type='codemap_stepstone'");
			foreach ($db->selectObjects('tasklist_task') as $item) {
				$search->original_id = $item->id;
				$search->title = ' ' . $item->name . ' ';
				$search->view_link = 'index.php?module=codemapmodule&action=stepstone_view&id='.$item->id;
				$search->body = ' ' . exponent_search_removeHTML($item->description) . ' ';
				$search->location_data = $item->location_data;
				$db->insertObject($search,'search');
			}
		}
		
		return true;
	}
}

?>
