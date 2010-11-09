<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Written and Designed by James Hunt
#
# Copyright (c) 2007 ACYSOS S.L. Modified by Ignacio Ibeas
# Added subcategory function
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################

class category {
	function form($object) {
		$i18n = exponent_lang_loadFile('datatypes/category.php');
		
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->name = '';
			$object->color = '';
			$object->file_id = 0;
			if (!isset($object->parent)) {
				die(SITE_403_REAL_HTML);
			}
		} else {
			$form->meta('id',$object->id);
		}
		
		$form->register('name',$i18n['name'],new textcontrol($object->name));
		$form->register('color',$i18n['color'],new textcontrol($object->color));

		if (!isset($object->id)) {
			// This is a new section, so we can add the positional dropdown
			// Pull the database object in from the global scope.
			global $db;
			// Retrieve all of the sections that are siblings of the new section
			$categories = $db->selectObjects('category',"parent=".$object->parent." AND location_data='".$object->location_data."'");
			
			if (count($categories) && $object->parent >= 0) {
				// Initialize the sorting subsystem so that we can order the categories
				// by rank, ascending, and get the proper ordering.
				if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
				usort($categories,'exponent_sorting_byRankAscending');
				
				// Generate the Position dropdown array.
				$positions = array($i18n['position_top']);
				foreach ($categories as $category) {
					$positions[] = sprintf($i18n['position_after'],$category->name);
				}
				$form->register('rank',$i18n['rank'],new dropdowncontrol(count($positions)-1,$positions));
			} else {
				// If there are no siblings, the new category gets the first
				// slot, with a rank of 0.
				$form->meta('rank',0);
			}
			// Store the category's parent in a hidden field, so that it comes through
			// when the form is submitted.
			$form->meta('parent',$object->parent);
		} else if ($object->parent >= 0) {
			// Allow them to change parents, but not if the category is outside of the hiearchy (parent > 0)
			$form->register('parent',$i18n['parent'],new dropdowncontrol($object->parent,category::levelDropdownControlArray($object->location_data,0,0,array($object->id),1)));
			$form->meta('rank',intval($object->rank));
		}
		
		$form->register('file',$i18n['icon'],new uploadcontrol());
		if ($object->file_id != 0) {
			$form->register(null,'', new htmlcontrol('&nbsp;&nbsp;&nbsp;'.$i18n['keep_old_icon'].'<br />'));
		}

		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		return $form;
	}
	
	function update($values,$object) {
		$object->name = $values['name'];
		$object->color = $values['color'];
		if (isset($values['rank'])) $object->rank = $values['rank'];
		if (isset($values['parent'])) $object->parent = $values['parent'];
		return $object;
	}

	/*
	 * Returns a flat representation of the full site hierarchy.
	 */
	function levelDropDownControlArray($mloc,$parent,$depth = 0,$ignore_ids = array(),$full=false) {
		$ar = array();
		if ($parent == 0 && $full) {
			$ar[0] = '&lt;Top of Hierarchy&gt;';
		}
		global $db;
		$nodes = $db->selectObjects("category","parent=".$parent." AND location_data='".$mloc."'");
		if (!defined('SYS_SORTING')) include_once(BASE.'subsystems/sorting.php');
		usort($nodes,'exponent_sorting_byRankAscending');
		foreach ($nodes as $node) {
			if (!in_array($node->id,$ignore_ids)) {
				$text = str_pad('',($depth+($full?1:0))*3,'.',STR_PAD_LEFT) . $node->name;
				$ar[$node->id] = $text;
				foreach (category::levelDropdownControlArray($mloc,$node->id,$depth+1,$ignore_ids,$full) as $id=>$text) {
					$ar[$id] = $text;
				}
			}
		}
		
		return $ar;
	}

function depth($id) {
		// To calculate a category's depth, we query its parents
		// until we find a parent with no parent (a top-level category).
		// The number of parents is the depth of the category.  For
		// instance, a top-level category has no parents and a depth
		// of 0.
	
		// Pull in the database object form the global scope.
		global $db;
		
		// Start out at depth 0.  The while loop will not execute if
		// the passed $id was that of a top-level category, so $depth
		// will still be set properly.
		$depth = 0;
		// Grab the category we were passed.
		$s = $db->selectObject("category","id=$id");
		while ($s->parent != 0) {
			// Section still has a parent.  Increment the depth counter.
			$depth++;
			// Get the category's parent's parent (grandparent)
			$s = $db->selectObject("category","id=".$s->parent);
		}
		return $depth;
	}
	
	function changeParent($category,$old_parent,$new_parent) {
		global $db;
		// Store new parent.
		$category->parent = $new_parent;
		
		$db->decrement('category','rank',1,'parent='.$old_parent . ' AND rank > ' . $category->rank);
		// Need to place this item at the end of the list of children for the new parent.
		$category->rank = $db->max('category','rank','parent','parent='.$new_parent);
		if ($category->rank === null) {
			$category->rank = 0;
		} else {
			$category->rank++;
		}
		
		return $category;
	}

	function levelTemplate($mloc,$parent, $depth = 0, $parents = array()) {
		if ($parent != 0) $parents[] = $parent;
		global $db;
		$nodes = array();
		$kids = $db->selectObjects("category","parent=".$parent." AND location_data='".serialize($mloc)."'");
		
		if (!defined('SYS_SORTING')) include_once(BASE.'subsystems/sorting.php');
		usort($kids,'exponent_sorting_byRankAscending');
		for ($i = 0; $i < count($kids); $i++) {
			$child = $kids[$i];
			//foreach ($kids as $child) {
			$child->numParents = count($parents);
			$child->depth = $depth;
			$child->first = ($i == 0 ? 1 : 0);
			$child->last = ($i == count($kids)-1 ? 1 : 0);
			$child->parents = $parents;
			$child->numChildren = $db->countObjects('category','parent='.$child->id);
			$nodes[] = $child;
			$nodes = array_merge($nodes,category::levelTemplate($mloc,$child->id,$depth+1,$parents));
		}
		return $nodes;
	}
}

?>