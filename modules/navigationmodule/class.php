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

$navigationmodule_cached_sections = null;

class navigationmodule {
	function name() { return 'Navigator'; }
	function author() { return 'James Hunt'; }
	function description() { return 'Allows users to navigate through pages on the site, and allows Administrators to manage the site page structure / hierarchy.'; }
	
	function hasContent() { return false; }
	function hasSources() { return false; }
	function hasViews()   { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = '') {
		global $db;
		$s = $db->selectObject('section','id='.$internal);
		if ($s && !navigationmodule::isPublic($s)) { 
			return array(
				'administrate'=>'Administrate',
				'view'=>'View Section',
				'manage'=>'Manage Section',
			);
		} else {
			return array(
				'administrate'=>'Administrate',
# Not too sure that this is a good idea, from a usability standpoint				
#				'view'=>'View Section',
				'manage'=>'Manage Section',
			);
		}
	}
	
	function getLocationHierarchy($loc) {
		// Initialize the location hierarchy
		$locs = array($loc);
		
		if ($loc->int == '') {
			// $location contains no section id in the int attribute,
			// so we can't genreate a lineage to the root.
			return $locs;
		}
		
		// Keep going and generate a lineage to the root
		global $db;
		$all = $db->selectObjectsIndexedArray('section','parent >= 0');
			
		$loc = pathos_core_makeLocation('navigationmodule','',@$all[$loc->int]->parent);
		while ($loc->int > 0) {
			$locs[] = $loc;
			$loc = pathos_core_makeLocation('navigationmodule','',@$all[$loc->int]->parent);
		}
		return $locs;
	}
	
	function show($view,$loc = null,$title = '') {
		$id = pathos_sessions_get('last_section');
		$current = null;
		$sections = navigationmodule::getHierarchy();
		foreach (array_keys($sections) as $sid) {
			if ($id == $sid) {
				$current = $sections[$sid];
				break;
			}
		}
		
		$template = new template('navigationmodule',$view,$loc);
		$template->assign('sections',$sections);
		$template->assign('current',$current);
		$template->assign('canManage',pathos_permissions_checkOnModule('manage','navigationmodule'));
		$template->assign('title',$title);
		
		$template->output();
	}
	
	
	function deleteIn($loc) {
		// Do nothing, no content
	}
	
	function copyContent($fromloc,$toloc) {
		// Do nothing, no content
	}
	
	function spiderContent($item = null) {
		// Do nothing, no content
		return false;
	}
	
	/*
	 * Retrieve either the entire hierarchy, or a subset of the hierarchy, as an array suitable for use
	 * in a dropdowncontrol.  This is used primarily by the section datatype for moving and adding
	 * sections to specific parts of the site hierarchy.
	 *
	 * @param $parent The id of the subtree parent.  If passed as 0 (the default), the entire subtree is parsed.
	 * @param $ignore_ids a value-array of IDs to be ignored when generating the list.  This is used
	 * when moving a section, since a section cannot be made a subsection of itself or any of its subsections.
	 */
	function hierarchyDropDownControlArray($parent = 0, $ignore_ids = array()) {
		$options = array();
		
		$depth_offset = 1;
		
		if ($parent == 0) {
			$options[0] = '&lt;Top of Hierarchy&gt;';
		} else {
			$section = $db->selectObject('section','id='.$parent);
			$depth_offset = -1 * $section->depth;
		}
		
		$ignore_depth = -1;
		
		foreach (navigationmodule::getHierarchy($parent) as $section) {
			if ($ignore_depth == -1 && in_array($section->id,$ignore_ids)) {
				$ignore_depth = $section->depth;
				continue;
			}
			
			if ($section->depth == $ignore_depth) {
				$ignore_depth = -1;
			}
			
			if ($ignore_depth == -1 || $section->depth < $ignore_depth) {
				if ($section->active == 1) {
					$options[$section->id] = str_pad('',($section->depth+$depth_offset)*3,'.',STR_PAD_LEFT) . $section->name;
				} else {
					$options[$section->id] = str_pad('',($section->depth+$depth_offset)*3,'.',STR_PAD_LEFT) . '(' . $section->name . ')';
				}
			}
		}
		return $options;
	}
	
	function levelDropDownControlArray($parent = 0) {
		$depth = 0;
		if ($parent != 0) {
			global $db;
			$section = $db->selectObject('section','id='.$parent);
			$depth = $section->depth;
		}
		
		$options = array();
		foreach (navigationmodule::getHierarchy($parent) as $section) {
			if ($section->parent == $parent) {
				if ($section->active == 1) {
					$options[$section->id] = $section->name;
				} else {
					$options[$section->id] = '(' . $section->name . ')';
				}
			}
		}
		return $options;
	}
	
	/*
	 * Returns a flat representation of the full site hierarchy.
	 */
	function getHierarchy($parent = 0) {
		$hier = navigationmodule::_getHierarchy();
		if ($parent == 0) return $hier;
		
		$new_hier = array();
		
		$subtree_depth = 0;
		foreach (array_keys($hier) as $sid) {
			if ($sid == $parent) {
				$subtree_depth = $hier[$sid]->depth;
			}
			
			if ($subtree_depth) { // Found our sub tree
				if ($hier[$sid]->depth > $subtree_depth) {
					$new_hier[$hier[$sid]->id] = $hier[$sid];
				} else {
					break;
				}
			}
		}
		
		return $new_hier;
	}
	
	function _getHierarchy() {
		global $db, $cached_sections;
		
		if ($cached_sections != null) {
			return $cached_sections;
		}
		
		$blocks = array();
		foreach ($db->selectObjects('section','parent != -1') as $section) {
			if (!isset($blocks[$section->parent])) {
				$blocks[$section->parent] = array();
			}
			$blocks[$section->parent][] = $section;
		}
		
		$hier = array();
		navigationmodule::_appendChildren($hier,$blocks,0);
		
		$cached_sections = $hier;
		return $hier;
	}
	
	function _appendChildren(&$master,&$blocks,$parent,$depth = 0, $parents = array()) {
		global $db;
		if ($parent != 0) $parents[] = $parent;
		
		if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
		usort($blocks[$parent],'pathos_sorting_byRankAscending');
		
		for ($i = 0; $i < count($blocks[$parent]); $i++) {
			$child = $blocks[$parent][$i];
			if ($child->public == 1 || navigationmodule::canView($child)) {
				$child->numParents = count($parents);
				$child->depth = $depth;
				$child->first = ($i == 0 ? 1 : 0);
				$child->last = ($i == count($blocks[$parent])-1 ? 1 : 0);
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
					
				$master[$child->id] = $child;
				if (isset($blocks[$child->id])) {
					navigationmodule::_appendChildren($master,$blocks,$child->id,$depth+1,$parents);
				}
			}
		}
	}
	
	function getTemplateHierarchyFlat($parent,$depth = 1) {
		global $db;
		
		$arr = array();
		$kids = $db->selectObjects('section_template','parent='.$parent);
		if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
		usort($kids,'pathos_sorting_byRankAscending');
		
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
			$template = $db->selectObject('section_template','id='.$template);
			$section->subtheme = $template->subtheme;
			$db->updateObject($section,'section');
		}
		$prefix = '@st'.$template->id;
		$refs = $db->selectObjects('locationref',"source LIKE '$prefix%'");
		
		// Copy all modules and content for this section
		foreach ($refs as $ref) {
			$src = substr($ref->source,strlen($prefix)) . $section->id;
			
			if (call_user_func(array($ref->module,'hasContent'))) {
				$oloc = pathos_core_makeLocation($ref->module,$ref->source);
				$nloc = pathos_core_makeLocation($ref->module,$src);
				
				call_user_func(array($ref->module,'copyContent'),$oloc,$nloc);
			}
		}
		
		// Grab sub pages
		foreach ($db->selectObjects('section_template','parent='.$template->id) as $t) {
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
		$section->page_title = $subtpl->page_title;
		$section->keywords = $subtpl->keywords;
		$section->description = $subtpl->description;
		
		$section->id = $db->insertObject($section,'section');
		
		navigationmodule::process_section($section,$subtpl);
	}
	
	function deleteLevel($parent) {
		global $db;
		$kids = $db->selectObjects('section','parent=$parent');
		foreach ($kids as $kid) {
			navigationmodule::deleteLevel($kid->id);
		}
		$secrefs = $db->selectObjects('sectionref','section='.$parent);
		foreach ($secrefs as $secref) {
			$loc = pathos_core_makeLocation($secref->module,$secref->source,$secref->internal);
			pathos_core_decrementLocationReference($loc,$parent);
			
			foreach ($db->selectObjects('locationref',"module='".$secref->module."' AND source='".$secref->source."' AND internal='".$secref->internal."' AND refcount = 0") as $locref) {
				if (class_exists($locref->module)) {
					$modclass = $locref->module;
					$mod = new $modclass();
					$mod->deleteIn(pathos_core_makeLocation($locref->module,$locref->source,$locref->internal));
				}
			}
			$db->delete('locationref',"module='".$secref->module."' AND source='".$secref->source."' AND internal='".$secref->internal."' AND refcount = 0");
		}
		$db->delete('sectionref','section='.$parent);
		$db->delete('section','parent=$parent');
	}
	
	function removeLevel($parent) {
		global $db;
		$kids = $db->selectObjects('section','parent='.$parent);
		foreach ($kids as $kid) {
			$kid->parent = -1;
			$db->updateObject($kid,'section');
			navigationmodule::removeLevel($kid->id);
		}
	}
	
	function canView($section) {
		// *** WARNING ***
		// This function CANNOT call navigationmodule::getHierarchy, since getHierarchy calls it.
		// The end result of doing so is a hard to track down infinite loop bug that causes PHP to crash
		// before anything useful gets echoed to the browser.
		global $db;
		$perms = array('view','manage','administrate');
		$loc = pathos_core_makeLocation('navigationmodule','',$section->id);
		while (true) {
			if ($section->public == 0) {
				// Not a public section.  Check permissions.
				return pathos_permissions_check($perms,$loc);
			} else { // Is public.  check parents.
				if ($section->parent <= 0) {
					// Out of parents, and since we are still checking, we haven't hit a private section.
					return true;
				} else {
					$section = $db->selectObject('section','id='.$section->parent);
					$loc->int = $section->id;
				}
			}
		}
	}
	
	function isPublic($section) {
		$hier = navigationmodule::getHierarchy();
		
		while (true) {
			if ($section->public == 0) {
				// Not a public section.  Check permissions.
				return false;
			} else { // Is public.  check parents.
				if ($section->parent <= 0) {
					// Out of parents, and since we are still checking, we haven't hit a private section.
					return true;
				} else {
					$section = $hier[$section->parent];
				}
			}
		}
	}
}

?>