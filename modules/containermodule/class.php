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

class containermodule {
	function name() { return "Container Module"; }
	function author() { return "James Hunt"; }
	function description() { return "Contains other modules"; }
	
	function hasContent() { return true; }
	function hasSources() { return true; }
	function hasViews()   { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = "") {
		return array(
			"administrate"=>"Administrate",
		//	"configure"=>"Configure",
			"add_module"=>"Add",
			"edit_module"=>"Edit",
			"delete_module"=>"Delete",
			"order_modules"=>"Change Order"
		);
	}
	
	function deleteIn($loc) {
		global $user;
		if ($user && $user->is_admin == 1) {
			include_once(BASE."datatypes/container.php");
			
			global $db;
			$containers = $db->selectObjects("container","external='" . serialize($loc) . "'");
			foreach ($containers as $container) {
				container::delete($container);
				$db->delete("container","id=".$container->id);
			}
		}
	}
	
	function show($view,$loc = null,$title = "") {
		$source_select = array();
		$clickable_mods = null; // Show all
		$dest = null;
		
		$singleview = "_container";
		$singlemodule = "containermodule";
		
		if (pathos_sessions_isset("source_select") && defined("SELECTOR")) {
			$source_select = pathos_sessions_get("source_select");
			$singleview = $source_select["view"];
			$singlemodule = $source_select["module"];
			$clickable_mods = $source_select["showmodules"];
			$dest = $source_select['dest'];
		}
		
		global $db;
		
		$container = null;
		if (!isset($this) || !isset($this->_hasParent) || $this->_hasParent == 0) {
			// Top level container.
			$container = $db->selectObject("container","external='".serialize(null)."' AND internal='".serialize($loc)."'");
			if ($container == null) {
				$container->external = serialize(null);
				$container->internal = serialize($loc);
				$container->view = $view;
				$container->title = $title;
				$container->id = $db->insertObject($container,"container");
			}
			
			if (!defined("PREVIEW_READONLY") || defined("SELECTOR")) $view = $container->view;
			$title = $container->title;
		}
		
		$template = new template("containermodule",$view,$loc);
		if ($dest) $template->assign("dest",$dest);
		$template->assign("singleview",$singleview);
		$template->assign("singlemodule",$singlemodule);
		
		$template->assign("top",$container);
		
		$containers = array();
		foreach ($db->selectObjects("container","external='" . serialize($loc) . "'") as $c) {
			$containers[$c->rank] = $c;
		}
		if (!defined("SYS_WORKFLOW")) include_once(BASE."subsystems/workflow.php");
		if (!defined("SYS_SORTING")) include_once(BASE."subsystems/sorting.php");
		#uasort($containers,"pathos_sorting_byRankAscending");
		ksort($containers);
		foreach (array_keys($containers) as $i) {
			$location = unserialize($containers[$i]->internal);
			$modclass = $location->mod;
			
			if (class_exists($modclass)) {
				$mod = new $modclass();
				
				ob_start();
				$mod->_hasParent = 1;
				$mod->show($containers[$i]->view,$location,$containers[$i]->title);

				$containers[$i]->output = trim(ob_get_contents());
				ob_end_clean();
				
				$policy = pathos_workflow_getPolicy($modclass,$location->src);
				
				$containers[$i]->info = array(
					"module"=>$mod->name(),
					"source"=>$location->src,
					"hasContent"=>$mod->hasContent(),
					"hasSources"=>$mod->hasSources(),
					"hasViews"=>$mod->hasViews(),
					"class"=>$modclass,
					"exportsContent"=>(method_exists($mod,"getContent") && method_exists($mod,"getContentType")),
					"supportsWorkflow"=>($mod->supportsWorkflow()?1:0),
					"workflowPolicy"=>($policy ? $policy->name : ""),
					"workflowUsesDefault"=>(pathos_workflow_moduleUsesDefaultPolicy($location->mod,$location->src) ? 1 : 0),
					"clickable"=>($clickable_mods == null || in_array($modclass,$clickable_mods))
				);
			} else {
				$containers[$i]->output = "The module '" . $location->mod . "' was not found in the system.";
				$containers[$i]->info = array(
					"module"=>"Unknown:".$location->mod,
					"source"=>$location->src,
					"hasContent"=>0,
					"hasSources"=>0,
					"hasViews"=>0,
					"class"=>$modclass,
					"exportsContent"=>0,
					"supportsWorkflow"=>0,
					"workflowPolicy"=>"",
					"workflowUsesDefault"=>0,
					"clickable"=>0
				);
			}
			$containers[$i]->moduleLocation = $location;
			
			$cloc = null;
			$cloc->mod = $loc->mod;
			$cloc->src = $loc->src;
			$cloc->int = $containers[$i]->id;
			if (!defined("HIDE_PERMISSIONS")) {
				$containers[$i]->permissions = array(
					"administrate"=>(pathos_permissions_check("administrate",$location) ? 1 : 0),
					"configure"=>(pathos_permissions_check("configure",$location) ? 1 : 0)
				);
			}
			$containers[$i]->hasPerms = pathos_permissions_checkOnSource($location->mod,$location->src);
		}
		
		$template->assign("containers",$containers);
		$template->assign("hidebox",pathos_sessions_get("_hidebox")+0);
		$template->assign("hasParent",(isset($this) && isset($this->_hasParent) ? 1 : 0));
		$template->register_permissions(
			array("administrate"/*,"configure"*/,"add_module","edit_module","delete_module","order_modules"),
			$loc
		);
		$template->output();
	}
	
	function copyContent($oloc,$nloc) {
		global $db;
		foreach ($db->selectObjects("container","external='".serialize($oloc)."'") as $c) {
			unset($c->id);
			$c->external = serialize($nloc);
			
			if (!$c->is_existing) { // Copy over content to a new source
				$oldinternal = unserialize($c->internal);
				$iloc = pathos_core_makeLocation($oldinternal->mod,"@random".uniqid(""));
				$c->internal = serialize($iloc);
				$db->insertObject($c,"container");
				
				// Now copy over content
				if (call_user_func(array($oldinternal->mod,"hasContent")) == true) {
					call_user_func(array($oldinternal->mod,"copyContent"),$oldinternal,$iloc);
					// Incrementors!
					pathos_core_incrementLocationReference($iloc,0); // SECTION
				}
			} else {
				$db->insertObject($c,"container");
				pathos_core_incrementLocationReference($iloc,0); // SECTION
			}
		}
	}
	
	function spiderContent($item = null) {
		// Do nothing, no content
	}
	
	function wrapOutput($modclass,$view,$loc = null,$title = "") {
		if (defined("SOURCE_SELECTOR") && strtolower($modclass) != "containermodule") {
			$container = null;
			$mod = new $modclass();
			
			ob_start();
			$mod->show($view,$loc,$title);
			$container->output = ob_get_contents();
			ob_end_clean();
			
			
			$source_select = pathos_sessions_get("source_select");
			$c_view = $source_select["view"];
			$c_module = $source_select["module"];
			$clickable_mods = $source_select["showmodules"];
			$dest = $source_select['dest'];
			
			$template = new template($c_module,$c_view,$loc);
			if ($dest) $template->assign("dest",$dest);
			
			$container->info = array(
				"module"=>$mod->name(),
				"source"=>$loc->src,
				"hasContent"=>$mod->hasContent(),
				"hasSources"=>$mod->hasSources(),
				"hasViews"=>$mod->hasViews(),
				"class"=>$modclass,
				"exportsContent"=>(method_exists($mod,"getContent") && method_exists($mod,"getContentType")),
				"clickable"=>($clickable_mods == null || in_array($modclass,$clickable_mods))
			);
			
			$template->assign("container",$container);
			$template->output();
		} else {
			call_user_func(array($modclass,"show"),$view,$loc,$title);
		}
	}
}

?>