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

class section {
	/*
	 * Common Form helper method
	 *
	 * This method, intended to be used solely by other methods of the
	 * section class, creates a base form that all other page types can
	 * build off of.  This form includes a name textbox, and either a rank
	 * meta field (hidden input) or a rank dropdown.
	 */
	function _commonForm(&$object) {
		// Create a new blank form.
		$form = new form();
		
		if (!isset($object->id)) {
			// This is a new section, so we need to set up some defaults.
			$object->name = '';
			$object->active = 1;
			$object->public = 1;
			$object->subtheme = '';
			
			$object->page_title = SITE_TITLE;
			$object->keywords = SITE_KEYWORDS;
			$object->description = SITE_DESCRIPTION;
			
			if (!isset($object->parent)) {
				// This is another precaution.  The parent attribute
				// should ALWAYS be set by the caller.
				$object->parent = 0;
			}
		} else {
			// If we are editing the section, we should store the section's id
			// in a hidden value, so that it comes through when the form is
			// submitted.
			$form->meta('id',$object->id);
		}
		
		// Store the section's parent in a hidden field, so that it comes through
		// when the form is submitted.
		$form->meta('parent',$object->parent);
		
		// The name of the section, as it will be linked in the section hierarchy.
		$form->register('name','Name',new textcontrol($object->name));
		
		if (!isset($object->id)) {
			// This is a new section, so we can add the positional dropdown
			// Pull the database object in from the global scope.
			global $db;
			// Retrieve all of the sections that are siblings of the new section
			$sections = $db->selectObjects('section','parent='.$object->parent);
			
			if (count($sections)) {
				// Initialize the sorting subsystem so that we can order the sections
				// by rank, ascending, and get the proper ordering.
				if (!defined('SYS_SORTING')) include_once(BASE.'subsystems/sorting.php');
				usort($sections,'pathos_sorting_byRankAscending');
				
				// Generate the Position dropdown array.
				$positions = array('At the Top');
				foreach ($sections as $section) {
					$positions[] = 'After "'.$section->name.'"';
				}
				$form->register('rank','Position',new dropdowncontrol(count($positions)-1,$positions));
			} else {
				// If there are no siblings, the new section gets the first
				// slot, with a rank of 0.
				$form->meta('rank',0);
			}
		}
		
		// Return the form to the calling scope, which should always be a
		// member method of this class.
		return $form;
	}

	/*
	 * Content Page Form method
	 *
	 * This method returns a Form object to be used when allowing the user
	 * to create a new normal Content Page or edit an existing one.
	 *
	 * @param Object $object The section object to build the form from.
	 *
	 * @return Form A form object that can be used to create a new section, or
	 *    edit an existing one.
	 */
	function form($object = null) {
		// Initialize the forms subsystem for use.
		if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		// Grab the basic form that all page types share
		// This has the name and positional dropdowns registered.
		// This call also initializes the section object, if it is not an existing section.
		$form = section::_commonForm($object);
		
		// Register the sub themes dropdown.
		$form->register('subtheme','Theme Variation',new dropdowncontrol($object->subtheme,pathos_theme_getSubThemes()));
		
		// Register the 'Active?' and 'Public?' checkboxes.
		$form->register('active','Active?',new checkboxcontrol($object->active));
		$form->register('public','Public?',new checkboxcontrol($object->public));
		
		// Register the Page Meta Data controls.
		$form->register('page_title','Page Title',new textcontrol($object->page_title));
		$form->register('keywords','Keywords',new texteditorcontrol($object->keywords,5,25));
		$form->register('description','Page Description',new texteditorcontrol($object->keywords,5,25));
		
		// Add a Submit / Cancel button.
		$form->register('submit','',new buttongroupcontrol('Save','','Cancel'));
		
		// Cleanup the Forms subsystem, for PHP5 and autoloading.
		pathos_forms_cleanup();
		
		// Return the form to the calling scope (usually an action in the navigation module).
		return $form;
	}
	
	/*
	 * External Alias Form method
	 *
	 * This method returns a form object to be used when allowing the user
	 * to create a new section that is actually a link to a website outside of the
	 * Exponent-managed site.
	 *
	 * @param Object $object The section object to build the form from.
	 *
	 * @return Form A form object that can be used to create a new section, or
	 *    edit an existing one.
	 */
	function externalAliasForm($object = null) {
		// Initialize the forms subsystem for use.
		if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		// Grab the basic form that all page types share
		// This has the name and positional dropdowns registered.
		// This call also initializes the section object, if it is not an existing section.
		$form = section::_commonForm($object);
		
		// Add a textbox the user can enter the external website's URL into.
		$form->register('external_link','Page',new textcontrol($object->external_link));
		
		// Add the'Public?' checkbox.  The 'Active?' checkbox is omitted, because it makes no sense.
		$form->register('public','Public?',new checkboxcontrol($object->public));
		
		// Add a Submit / Cancel button.
		$form->register('submit','',new buttongroupcontrol('Save','','Cancel'));
		
		// Cleanup the Forms subsystem, for PHP5 and autoloading.
		pathos_forms_cleanup();
		
		// Return the form to the calling scope (usually an action in the navigation module).
		return $form;
	}
	
	/*
	 * Internal Alias Form method
	 *
	 * This method returns a form object to be used when allowing the user
	 * to create a new section that is actually a link to another page in the
	 * Exponent site hierarchy.
	 *
	 * @param Object $object The section object to build the form from.
	 *
	 * @return Form A form object that can be used to create a new section, or
	 *    edit an existing one.
	 */
	function internalAliasForm($object = null) {
		// Initialize the forms subsystem for use.
		if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		// Grab the basic form that all page types share
		// This has the name and positional dropdowns registered.
		// This call also initializes the section object, if it is not an existing section.
		$form = section::_commonForm($object);
		
		// Add a dropdown to allow the user to choose an internal page.
		$form->register('internal_id','Page',new dropdowncontrol(0,navigationmodule::levelDropDownControlArray(0,0)));
		
		// Add the'Public?' checkbox.  The 'Active?' checkbox is omitted, because it makes no sense.
		$form->register('public','Public?',new checkboxcontrol($object->public));
		
		// Add a Submit / Cancel button.
		$form->register('submit','',new buttongroupcontrol('Save','','Cancel'));
		
		// Cleanup the Forms subsystem, for PHP5 and autoloading.
		pathos_forms_cleanup();
		
		// Return the form to the calling scope (usually an action in the navigation module).
		return $form;
	}
	
	/*
	 * Pageset Form method
	 *
	 * This method returns a form object to be used when allowing the user
	 * to create a new section using a user-defined Pageset.
	 *
	 * @param Object $object The section object to build the form from.
	 *
	 * @return Form A form object that can be used to create a new section, or
	 *    edit an existing one.
	 */
	function pagesetForm($object = null) {
		// Initialize the forms subsystem for use.
		if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		// Grab the basic form that all page types share
		// This has the name and positional dropdowns registered.
		// This call also initializes the section object, if it is not an existing section.
		$form = section::_commonForm($object);
		
		// Add a dropdown to allow the user to choose which pageset they want.
		// Pull the database object in from the global scope.
		global $db;
		// A holding array, which will become the source of the dropdown
		$pagesets = array();
		foreach ($db->selectObjects('section_template','parent=0') as $pageset) {
			// Grab each pageset and store its name and id.  The id will be used when updating.
			$pagesets[$pageset->id] = $pageset->name;
		}
		$form->register('pageset','Pageset',new dropdowncontrol(0,$pagesets));
		
		// Add the'Public?' checkbox.  The 'Active?' checkbox is omitted, because it makes no sense.
		$form->register('public','Public?',new checkboxcontrol($object->public));
		
		// Add a Submit / Cancel button.
		$form->register('submit','',new buttongroupcontrol('Save','','Cancel'));
		
		// Cleanup the Forms subsystem, for PHP5 and autoloading.
		pathos_forms_cleanup();
		
		// Return the form to the calling scope (usually an action in the navigation module).
		return $form;
	}
	
	// Update methods
	
	/*
	 * Update Object helper method
	 *
	 * This method is a complement to _commonForm, and updates the name
	 * and rank of the passed object using the passed values.
	 *
	 * @param array $values The data received from the form submission
	 * @param object $object The section object to update
	 *
	 * @return object The updated section object.
	 */
	function _updateCommon($values,$object) {
		$object->name = $values['name'];
		if (isset($values['rank'])) $object->rank = $values['rank'];
		if (isset($values['parent'])) $object->parent = $values['parent'];
		return $object;
	}
	
	/*
	 * Content Page Update method
	 *
	 * This method updates the passed section object's attributes using
	 * the passed values.
	 *
	 * @param array $values The data received from the form submission
	 * @param object $object The section object to update
	 *
	 * @return object The updated section object.
	 */
	function update($values,$object) {
		$object = section::_updateCommon($values,$object);
		$object->subtheme = $values['subtheme'];
		$object->active = isset($values['active']);
		$object->public = isset($values['public']);
		$object->page_title = ($values['page_title'] != SITE_TITLE ? $values['page_title'] : "");
		$object->keywords = ($values['keywords'] != SITE_KEYWORDS ? $values['keywords'] : "");
		$object->description = ($values['description'] != SITE_DESCRIPTION ? $values['description'] : "");
		return $object;
	}
	
	/*
	 * External Alias Update method
	 *
	 * This method updates the passed section object's attributes using
	 * the passed values.
	 *
	 * @param array $values The data received from the form submission
	 * @param object $object The section object to update
	 *
	 * @return object The updated section object.
	 */
	function updateExternalAlias($values,$object) {
		$object = section::_updateCommon($values,$object);
		
		$object->active = 1;
		$object->public = isset($values['public']);
		
		$object->alias_type = 1;
		$object->external_link = $values['external_link'];
		if (!pathos_core_URLisValid($object->external_link)) {
			$object->external_link = 'http://' . $object->external_link;
		}
		return $object;
	}
	
	/*
	 * Internal Alias Update method
	 *
	 * This method updates the passed section object's attributes using
	 * the passed values.
	 *
	 * @param array $values The data received from the form submission
	 * @param object $object The section object to update
	 *
	 * @return object The updated section object.
	 */
	function updateInternalAlias($values,$object) {
		$object = section::_updateCommon($values,$object);
		
		$object->active = 1;
		$object->public = isset($values['public']);
		
		$object->alias_type = 2;
		global $db;
		// We need to make sure we don't point to another link
		$section = $db->selectObject('section','id='.$values['internal_id']);
		while ($section->alias_type == 2) {
			// Find what it is pointing to.
			$section = $db->selectObject('section','id='.$section->internal_id);
		}
		// Pull the destination section's id into the internal_id field.  This works because
		// if the while loop didn't execute, we had a 'normal' page to begin with.  This check
		// doesn't guard against pointing an internal link to a section that is set up to
		// an external link -- that check will need to be done in the navigation module itself.
		$object->internal_id = $section->id;
		// Set the active state of the new section from the linked section.  The caller is
		// expected to catch this if a link to an inactive section is made, and that behaviour
		// is undesired.
		$object->active = $section->active;
		return $object;
	}
	
	/*
	 * Pageset Update method
	 *
	 * This method updates the passed section object's attributes using
	 * the passed values.
	 *
	 * @param array $values The data received from the form submission
	 * @param object $object The section object to update
	 *
	 * @return object The updated section object.
	 */
	function updatePageset($values,$object) {
		$object = section::_updateCommon($values,$object);
		
		$object->active = 1;
		$object->public = isset($values['public']);
		
		// Can't really do much with pageset updating, because we
		// need to save the section before we can add subsections or copy
		// any content.
		return $object;
	}
	
	// The following are helper functions for dealing with the Section datatype.
	
	/*
	 * Determine Section Depth
	 *
	 * This method looks at a section ID, and figures out how deep in the
	 * site hierarchy it is, and returns that number.  A top-level section has a
	 * depth of 0, it's children all have a depth of 1, and so on.
	 *
	 * @param integer $id The id of the section to find the depth count for.
	 *
	 * @return integer The depth of the section.
	 */
	function depth($id) {
		// To calculate a section's depth, we query its parents
		// until we find a parent with no parent (a top-level section).
		// The number of parents is the depth of the section.  For
		// instance, a top-level section has no parents and a depth
		// of 0.
	
		// Pull in the database object form the global scope.
		global $db;
		
		// Start out at depth 0.  The while loop will not execute if
		// the passed $id was that of a top-level section, so $depth
		// will still be set properly.
		$depth = 0;
		// Grab the section we were passed.
		$s = $db->selectObject("section","id=$id");
		while ($s->parent != 0) {
			// Section still has a parent.  Increment the depth counter.
			$depth++;
			// Get the section's parent's parent (grandparent)
			$s = $db->selectObject("section","id=".$s->parent);
		}
		return $depth;
	}
}

?>