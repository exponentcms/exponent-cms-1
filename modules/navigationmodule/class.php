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

class navigationmodule {
	function name() { return "Navigator"; }
	function author() { return "James Hunt"; }
	function description() { return "Allows users to navigate through pages on the site, and allows Administrators to manage the site page structure / hierarchy."; }
	
	function hasContent() { return false; }
	function hasSources() { return false; }
	function hasViews()   { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = "") {
		return array(
			"view"=>"View Section"
		);
	}
	
	function show($view,$loc = null,$title = "") {
		$id = pathos_sessions_get("last_section");
		$current = null;
		$sections = navigationmodule::levelTemplate(0,0);
		foreach ($sections as $section) {
			if ($section->id == $id) {
				$current = $section;
				break;
			}
		}
		
		$template = new Template("navigationmodule",$view,$loc);
		$template->assign("sections",$sections);
		$template->assign("current",$current);
		//$template->assign("canManage",pathos_permissions_checkOnModule("administrate","navigationmodule"));
		global $user;
		$template->assign("canManage",($user && $user->is_acting_admin?1:0));
		$template->assign("title",$title);
		
		$template->output();
	}
	
	
	function deleteIn($loc) {
	
	}
	
	function copyContent($fromloc,$toloc) {
	
	}
	
	function spiderContent($item = null) {
		// Do nothing, no content
	}
	
	/*
	 * @deprecated is it?
	 */
	function levelShowDropdown($parent,$depth=0,$default=0) {
		$html = "";
		global $db;
		$nodes = $db->selectObjects("section","parent=$parent");
		if (!defined("SYS_SORTING")) include_once(BASE."subsystems/sorting.php");
		usort($nodes,"pathos_sorting_byRankAscending");
		foreach ($nodes as $node) {
			if ($node->public == 1 || pathos_permissions_check("view",pathos_core_makeLocation("navigationmodule","",$node->id))) {
				$html .= "<option value='" . $node->id . "' ";
				if ($default == $node->id) $html .= "selected";
				$html .= ">";
				if ($node->active == 1) {
					$html .= str_pad("",$depth*3,".",STR_PAD_LEFT) . $node->name;
				} else {
					$html .= str_pad("",$depth*3,".",STR_PAD_LEFT) . "(".$node->name.")";
				}
				$html .= "</option>";
				$html .= navigationmodule::levelShowDropdown($node->id,$depth+1,$default);
			}
		}
		return $html;
	}
	
	/*
	 * @deprecated is it?
	 */
	function levelDropDownControlArray($parent,$depth = 0) {
		$ar = array();
		global $db;
		$nodes = $db->selectObjects("section","parent=$parent");
		if (!defined("SYS_SORTING")) include_once(BASE."subsystems/sorting.php");
		usort($nodes,"pathos_sorting_byRankAscending");
		foreach ($nodes as $node) {
			if ($node->public == 1 || pathos_permissions_check("view",pathos_core_makeLocation("navigationmodule","",$node->id))) {
				if ($node->active == 1) {
					$text = str_pad("",$depth*3,".",STR_PAD_LEFT) . $node->name;
				} else {
					$text = str_pad("",$depth*3,".",STR_PAD_LEFT) . "(".$node->name.")";
				}
				$ar[$node->id] = $text;
				foreach (navigationmodule::levelDropdownControlArray($node->id,$depth+1) as $id=>$text) {
					$ar[$id] = $text;
				}
			}
		}
		return $ar;
		
	}
	
	function levelTemplate($parent, $depth = 0, $parents = array()) {
		if ($parent != 0) $parents[] = $parent;
		global $db;
		$nodes = array();
		$kids = $db->selectObjects("section","parent=$parent");
		if (!defined("SYS_SORTING")) include_once(BASE."subsystems/sorting.php");
		usort($kids,"pathos_sorting_byRankAscending");
		for ($i = 0; $i < count($kids); $i++) {
			$child = $kids[$i];
			//foreach ($kids as $child) {
			if ($child->public == 1 || pathos_permissions_check("view",pathos_core_makeLocation("navigationmodule","",$child->id))) {
				$child->numParents = count($parents);
				$child->depth = $depth;
				$child->first = ($i == 0 ? 1 : 0);
				$child->last = ($i == count($kids)-1 ? 1 : 0);
				$child->parents = $parents;
				
				// Generate the link attribute base on alias type.
				if ($child->alias_type == 1) {
					// External link.  Set the link to the configured website URL.
					// This is guaranteed to be a full URL because of the
					// section::updateExternalAlias() method in datatypes/section.php
					$child->link = $child->external_link;
				} else if ($child->alias_type == 2) {
					// Internal link.
					
					// Need to check and see if the internal_id is pointing at an external link.
					$dest = $db->selectObject('section','id='.$child->internal_id);
					if ($dest->alias_type == 1) {
						// This internal alias is pointing at an external alias.
						// Use the external_link of the destination section for the link
						$child->link = $dest->external_link;
					} else {
						// Pointing at a regular section.  This is guaranteed to be
						// a regular section because aliases cannot be turned into sections,
						// (and vice-versa) and because the section::updateInternalLink
						// does 'alias to alias' dereferencing before the section is saved
						// (see datatypes/section.php)
						$child->link = pathos_core_makeLink(array('section'=>$child->internal_id));
					}
				} else {
					// Normal link.  Just create the URL from the section's id.
					$child->link = pathos_core_makeLink(array('section'=>$child->id));
				}
					
				$nodes[] = $child;
				$nodes = array_merge($nodes,navigationmodule::levelTemplate($child->id,$depth+1,$parents));
			}
		}
		return $nodes;
	}
	
	function getTemplateHierarchyFlat($parent,$depth = 1) {
		global $db;
		
		$arr = array();
		$kids = $db->selectObjects("section_template","parent=".$parent);
		if (!defined("SYS_SORTING")) include_once(BASE."subsystems/sorting.php");
		usort($kids,"pathos_sorting_byRankAscending");
		
		for ($i = 0; $i < count($kids); $i++) {
			$page = $kids[$i];
			
			$page->depth = $depth;
			$page->first = ($i == 0 ? 1 : 0);
			$page->last = ($i == count($kids)-1 ? 1 : 0);
			$arr[] = $page;
			$arr = array_merge($arr,navigationmodule::getTemplateHierarchyFlat($page->id,$depth + 1));
		}
		return $arr;
	}
	
	function process_section($section,$template) {
		global $db;
		if (!is_object($template)) {
			$template = $db->selectObject("section_template","id='".$template."'");
			$section->subtheme = $template->subtheme;
			$db->updateObject($section,"section");
		}
		$prefix = "@st".$template->id;
		$refs = $db->selectObjects("locationref","source LIKE '$prefix%'");
		
		// Copy all modules and content for this section
		foreach ($refs as $ref) {
			$src = substr($ref->source,strlen($prefix));
			if ($src == "@section" || substr($src,0,-1) == "_") {
				$src .= $section->id;
			}
			
#			echo $src;
			
			if (call_user_func(array($ref->module,"hasContent"))) {
				$oloc = pathos_core_makeLocation($ref->module,$ref->source);
				$nloc = pathos_core_makeLocation($ref->module,$src);
				
#				echo $ref->module . "<br />";
				call_user_func(array($ref->module,"copyContent"),$oloc,$nloc);
			}
		}
		
		// Grab sub pages
		foreach ($db->selectObjects("section_template","parent=".$template->id) as $t) {
			navigationmodule::process_subsections($section,$t);
		}
		
	}
	
	function process_subsections($parent_section,$subtpl) {
		global $db;
		
		$section = null;
		$section->parent = $parent_section->id;
		$section->name = $subtpl->name;
		$section->subtheme = $subtpl->subtheme;
		$section->active = $subtpl->active;
		$section->public = $subtpl->public;
		$section->rank = $subtpl->rank;
		
		$section->id = $db->insertObject($section,"section");
		
		navigationmodule::process_section($section,$subtpl);
	}
	
	function deleteLevel($parent) {
		global $db;
		$kids = $db->selectObjects("section","parent=$parent");
		foreach ($kids as $kid) {
			navigationmodule::deleteLevel($kid->id);
		}
		$secrefs = $db->selectObjects("sectionref","section=".$parent);
		foreach ($secrefs as $secref) {
			$loc = pathos_core_makeLocation($secref->module,$secref->source,$secref->internal);
			pathos_core_decrementLocationReference($loc,$parent);
			
			foreach ($db->selectObjects("locationref","module='".$secref->module."' AND source='".$secref->source."' AND internal='".$secref->internal."' AND refcount = 0") as $locref) {
				$modclass = $locref->module;
				$mod = new $modclass();
				$mod->deleteIn(pathos_core_makeLocation($locref->module,$locref->source,$locref->internal));
			}
			$db->delete("locationref","module='".$secref->module."' AND source='".$secref->source."' AND internal='".$secref->internal."' AND refcount = 0");
		}
		$db->delete("sectionref","section=".$parent);
		$db->delete("section","parent=$parent");
	}
}

?>