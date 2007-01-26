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
# $Id: class.php,v 1.6 2005/05/09 06:02:27 filetreefrog Exp $
##################################################

class tasklistmodule {
	function name() { return "Todo List"; }
	function description() { return "Allows you to set up a series of todo items, rank them by priority, and track your completion status."; }
	function author() { return "OIC Group, Inc."; }
	
	function hasSources() { return true; }
	function hasContent() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = "") {
		return array(
			'administrate'=>'Administrate',
			'configure'=>'Configure',
			'create'=>'Create Tasks',
			'edit'=>'Edit Tasks',
			'delete'=>'Delete Tasks'
		);
	}
	
	function show($view,$loc = null, $title = "") {
		global $db;
		
		$config = $db->selectObject('tasklistmodule_config',"location_data='".serialize($loc)."'");
		if ($config == null) {
			$config->show_completed = 1;
		}
		
		$where = '';
		if ($config->show_completed == 0) {
			$where = ' AND completion < 100';
		}
		
		$tasks = array();
		foreach ($db->selectObjects('tasklist_task',"location_data='".serialize($loc)."'".$where) as $t) {
			$t->left = 100 - $t->completion;
			$tasks[] = $t;
		}
		
		if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
		if (!function_exists('exponent_sorting_byPriorityDescending')) {
			function exponent_sorting_byPriorityDescending($a,$b) {
				return ($a->priority < $b->priority ? 1 : -1);
			}
		}
		usort($tasks,'exponent_sorting_byPriorityDescending');
		
		$template = new template('tasklistmodule',$view,$loc);
		$template->assign('moduletitle',$title);
		$template->assign('tasks',$tasks);
		$template->assign('num_completed',$db->countObjects('tasklist_task',"location_data='".serialize($loc)."' AND completion >= 100"));
		$template->assign('num_uncompleted',$db->countObjects('tasklist_task',"location_data='".serialize($loc)."' AND completion < 100"));
		$template->register_permissions(array('administrate','configure','create','edit','delete'),$loc);
		
		$template->output();
	}
	
	function deleteIn($loc) {
		global $db;
		$db->delete('tasklist_task',"location_data='".serialize($loc)."'");
	}
	
	function copyContent($oloc,$nloc) {
		global $db;
		
		$locdata = serialize($nloc);
		foreach ($db->selectObjects('tasklist_task',"location_data='".serialize($oloc)."'") as $t) {
			$t->location_data = $locdata;
			
			unset($t->id);
			$db->insertObject($t,'tasklist_task');
		}
	}

	function searchName() {
		return "Tasks";
	}
	
	function spiderContent($item = null) {
		global $db;
		
		if (!defined('SYS_SEARCH')) require_once(BASE.'subsystems/search.php');
		
		$search = null;
		$search->category = 'Todo Items';
		$search->ref_module = 'tasklistmodule';
		$search->ref_type = 'tasklist_task';
		$search->view_link = '';
		
		if ($item) {
			$db->delete('search',"ref_module='tasklistmodule' AND ref_type='tasklist_task' AND original_id=" . $item->id);
			$search->original_id = $item->id;
			$search->title = ' ' . $item->name . ' ';
			$search->body = ' ' . exponent_search_removeHTML($item->description) . ' ';
			$search->location_data = $item->location_data;
			$db->insertObject($search,'search');
		} else {
			$db->delete('search',"ref_module='tasklistmodule' AND ref_type='tasklist_task'");
			foreach ($db->selectObjects('tasklist_task') as $item) {
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
