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

/**
 * Modules Subsystem
 *
 * Module Management routines.
 *
 * @package		Subsystems
 * @subpackage	Modules
 *
 * @author		James Hunt
 * @copyright		2004 James Hunt and the OIC Group, Inc.
 * @version		0.95
 */

/**
 * SYS flag
 *
 * The definition of this constant lets other parts of the system know 
 * that the subsystem has been included for use.
 */
define("SYS_MODULES",1);

/**
 * Initialize the Module Subsystem
 *
 * This includes all modules available to the system, for use later.
 */
function pathos_modules_initialize() {
	if (is_readable(BASE."modules")) {
		$dh = opendir(BASE."modules");
		while (($file = readdir($dh)) !== false) {
			if (is_readable(BASE."modules/$file/class.php")) {
				include_once(BASE."modules/$file/class.php");
			}
		}
	}
}

/**
 * List Available Modules
 *
 * Looks through the modules directory and returns a list of
 * all module class names that exist in the system.  No activity
 * state check is made, so inactive modules will also be listed.
 *
 * @return Array the list of module class names.
 */
function pathos_modules_list() {
	$mods = array();
	if (is_readable(BASE."modules")) {
		$dh = opendir(BASE."modules");
		while (($file = readdir($dh)) !== false) {
			if (substr($file,-6,6) == "module") $mods[] = $file;
		}
	}
	return $mods;
}

/**
 * List Available Active Modules
 *
 * Looks through the database returns a list of all module class
 * names that exist in the system and have been turned on by
 * the administrator.  Inactive modules will not be included.
 *
 * @return Array the list of active module class names.
 */
function pathos_modules_listActive() {
	global $db;
	$modulestates = $db->selectObjects("modstate","active='1'");
	$modules = array();
	foreach ($modulestates as $state) {
		if (class_exists($state->module)) $modules[] = $state->module;
	}
	return $modules;
}

/**
 * List All Standard Views for a Module
 *
 * Looks through the module's views directory and returns
 * all non-internal views that are found there.  The current theme
 * is not consulted.
 *
 * @param string $module The classname of the module to get views for.
 *
 * @return Array an array of all standard view names.  This array is unsorted.
 */
function pathos_modules_views($module) {
	$views = array();
	if (is_readable(BASE."modules/$module/views")) {
		$dh = opendir(BASE."modules/$module/views");
		while (($file = readdir($dh)) !== false) {
			if (substr($file,-4,4) == ".tpl" && substr($file,0,1) != "_") $views[] = substr($file,0,-4);
		}
	}
	if (is_readable(THEME_BASE."modules/$module/views")) {
		$dh = opendir(THEME_BASE."modules/$module/views");
		while (($file = readdir($dh)) !== false) {
			if (substr($file,-4,4) == ".tpl" && substr($file,0,1) != "_") {
				$view = substr($file,0,-4);
				if (!in_array($view,$views)) $views[] = $view;
			}
		}
	}
	return $views;
}

/**
 * Resolves a view name to a real .tpl file
 *
 * Consults both the theme and the standard views directory (in that order)
 * to determine where the template code for a view is stored.
 *
 * @return string The absolute path to the template file, or "" if no matching view file found.
 */
function pathos_modules_getViewFile($module,$view,$recurse = true) {
	if (is_readable(BASE."themes/".DISPLAY_THEME."/modules/$module/views/$view.tpl")) return BASE."themes/".DISPLAY_THEME."/modules/$module/views/$view.tpl";
	else if (is_readable(BASE . "modules/$module/views/$view.tpl")) return BASE . "modules/$module/views/$view.tpl";
	else if ($recurse && $view != "Default") return pathos_modules_getViewFile($module,"Default");
	else return "";
}

/**
 * Get Javascript Validation Script Filename
 *
 * Looks through the current theme and standard js directories to find
 * the javascript form validation file for a given form in a module.
 *
 * @param string $module The classname of the module.
 * @param string $formname The name of the form
 *
 * @return The filename of the Javascript Validation script, or "" if one was not found.
 */
function pathos_modules_getJSValidationFile($module,$formname) {
	if (is_readable(BASE."themes/".DISPLAY_THEME."/modules/$module/js/$formname.validate.js")) return PATH_RELATIVE . "themes/".DISPLAY_THEME."/modules/$module/js/$formname.validate.js";
	else if (is_readable(BASE."modules/$module/js/$formname.validate.js")) return PATH_RELATIVE."modules/$module/js/$formname.validate.js";
	return "";
}

/**
 * Populate Template for module manager
 *
 * THIS NEEDS CHANGED
 */
function pathos_modules_moduleManagerFormTemplate($template) {
	$modules = pathos_modules_list();
	natsort($modules);
	
	global $db;
	$moduleInfo = array();
	foreach ($modules as $module) {
		$mod = new $module();
		$modstate = $db->selectObject("modstate","module='$module'");
		
		$moduleInfo[$module] = null;
		$moduleInfo[$module]->class = $module;
		$moduleInfo[$module]->name = $mod->name();
		$moduleInfo[$module]->author = $mod->author();
		$moduleInfo[$module]->description = $mod->description();
		$moduleInfo[$module]->active = ($modstate != null ? $modstate->active : 0);
	}
	uasort($moduleInfo,"pathos_sorting_byNameAscending");
	
	$template->assign("modules",$moduleInfo);
	return $template;
}

/**
 * Verifies Module File Structure
 *
 * This is used to verify that a module directory has all the required
 * directories and files.  Used mainly by the module upload feature to
 * ensure that the uploaded archive does in fact contain a module.
 *
 * @param string $basedir The absolute path to the module directory
 *
 * @return boolean true if the directory has valid module structure and false if it does not.
 */
function pathos_modules_verifyModule($basedir) {
	// class.php
	if (	!file_exists("$basedir/class.php") ||
		!is_file("$basedir/class.php") ||
		!is_readable("$basedir/class.php")	) return false;
	
	// actions
	if (file_exists("$basedir/actions") && (
		!is_dir("$basedir/actions") ||
		!is_readable("$basedir/actions"))) return false;
	
	// views
	if (file_exists("$basedir/views") && (
		!is_dir("$basedir/views") ||
		!is_readable("$basedir/views"))) return false;
	
	// views_c
	if (file_exists("$basedir/views_c") && (
		!is_dir("$basedir/views_c") ||
		!is_readable("$basedir/views_c"))) return false;
		
	return true;
}

/**
 * Check Module Existence
 *
 * Checks to see if a module exists in the system.  No activity
 * check is made, so inactive modules still exist, according to this
 * method (no this is not a bug)
 *
 * @return boolean true of the module exists, false if it was not found.
 */
function pathos_modules_moduleExists($name) {
	return (file_exists(BASE."modules/$name") && is_dir(BASE."modules/$name") && is_readable(BASE."modules/$name/class.php"));
}

?>