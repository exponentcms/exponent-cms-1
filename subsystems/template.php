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

/* exdoc
 * The definition of this constant lets other parts of the system know 
 * that the subsystem has been included for use.
 * @node Subsystems:Template
 */
define("SYS_TEMPLATE",1);

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
define("SYS_TEMPLATE_CLEAR_ALL",  1);
/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
define("SYS_TEMPLATE_CLEAR_USERS",2);

define('TEMPLATE_FALLBACK_VIEW',BASE."views/viewnotfound.tpl");

include_once(BASE."external/Smarty/libs/Smarty.class.php");

class basetemplate {
	var $tpl;
	// This will be used by modules on the outside, for retrieving view configs.
	var $viewfile = "";
	var $view = "";
	var $viewdir = "";
	
	function assign($var,$val) {
		$this->tpl->assign($var,$val);
	}
	
	function output() {
		$this->tpl->display($this->view.".tpl");
	}
	
	function register_permissions($perms,$locs) {
		$permissions_register = array();
		if (!is_array($perms)) $perms = array($perms);
		if (!is_array($locs)) $locs = array($locs);
		foreach ($perms as $perm) {
			foreach ($locs as $loc) {
				$permissions_register[$perm] = (pathos_permissions_check($perm,$loc) ? 1 : 0);
			}
		}
		$this->tpl->assign("permissions",$permissions_register);
	}
	
	function render() { // Caching support?
		return $this->tpl->fetch($this->view.".tpl");
	}
}
/*
 * Wraps the template system in use, to provide a uniform and consistent
 * interface to templates.
 */
class template extends basetemplate {	
	var $module = "";
	
	function template($module,$view = null,$loc=null,$caching=false) {
		// Set up the Smarty template variable we wrap around.
		$this->tpl = new Smarty();
		$this->tpl->php_handling = SMARTY_PHP_REMOVE;
		$this->tpl->plugins_dir[] = BASE."plugins";
		
		$this->viewfile = pathos_template_getModuleViewFile($module,$view);
		$this->viewdir = realpath(dirname($this->viewfile));
		
		$this->view = substr(basename($this->viewfile),0,-4);
		$this->tpl->template_dir = $this->viewdir;
		
		// Make way for i18n
		// $this->tpl->compile_dir = $this->viewdir."_c";
		$this->tpl->compile_dir = BASE.'/views_c';
		$this->tpl->compile_id = md5($this->viewfile);
		
		$this->tpl->assign("__view",$this->view);
		if ($loc == null) $loc = pathos_core_makeLocation($module);
		$this->tpl->assign("__loc",$loc);
		$this->tpl->assign("__redirect",pathos_flow_get());
		
		// View Config
		global $db;
		$container = $db->selectObject("container","internal='".serialize($loc)."'");
		$viewconfig = ($container->view_data != "" ? unserialize($container->view_data) : array());
		$this->tpl->assign("__viewconfig",$viewconfig);
	}
	
	
}

/*
 * Form Template Wrapper
 *
 * This class wraps is used for site wide forms.  
 *
 * @package Subsystems
 * @subpackage Template
 */
class formtemplate extends basetemplate {
	function formtemplate($formtype,$view) {
	
		$this->tpl = new Smarty();
		$this->tpl->php_handling = SMARTY_PHP_REMOVE;
		$this->tpl->plugins_dir[] = BASE."plugins";
		
		if (is_readable(THEME_ABSOLUTE."forms/".$formtype."/".$view.".tpl")) {
			$this->viewfile = THEME_ABSOLUTE."forms/".$formtype."/".$view.".tpl";
		} else if (is_readable(BASE."forms/".$formtype."/".$view.".tpl")) {
			$this->viewfile = BASE."forms/".$formtype."/".$view.".tpl";
		} else {
			$this->viewfile = TEMPLATE_FALLBACK_VIEW;
		}
		
		$this->view = substr(basename($this->viewfile),0,-4);
		//echo 'this is the view'.$this->view;
		$this->viewdir = realpath(dirname($this->viewfile));
		
		$this->tpl->template_dir = $this->viewdir;
		$this->tpl->compile_dir = $this->viewdir.'_c';
		
		$this->tpl->assign("__view",$this->view);
		
		$this->tpl->assign("__redirect",pathos_flow_get());
	}
	
	
}

class filetemplate extends basetemplate {
	function filetemplate($file) {
		$this->tpl = new Smarty();
		//$this->tpl->security = true;
		$this->tpl->php_handling = SMARTY_PHP_REMOVE;
		$this->tpl->plugins_dir[] = BASE."plugins";
		
		$this->view = substr(basename($file),0,-4);
		$this->viewdir = realpath(dirname($file));
		
		$this->tpl->template_dir = $this->viewdir;
		$this->tpl->compile_dir = $this->viewdir."_c";
		
		$this->tpl->assign("__view",$view);
		$this->tpl->assign("__redirect",pathos_flow_get());
	}
}

/*
 * Standalone Template Class
 *
 * A standalone template is a template (tpl) file found in either
 * THEME_ABSOLUTE/views or BASE/views, which uses
 * the corresponding views_c directory for compilation.
 * 
 * @param string $view The name of the standalone view.
 */
class standalonetemplate extends basetemplate {
	function standalonetemplate($view) {
		$this->tpl = new Smarty();
		//$this->tpl->security = true;
		$this->tpl->php_handling = SMARTY_PHP_REMOVE;
		$this->tpl->plugins_dir[] = BASE."plugins";
		
		$file = pathos_template_getViewFile($view);
		
		$this->view = substr(basename($file),0,-4);
		$this->viewdir = realpath(dirname($file) . "/..") . "/views";
		
		$this->tpl->template_dir = $this->viewdir;
		$this->tpl->compile_dir = $this->viewdir."_c";
		
		$this->tpl->assign("__view",$view);
		$this->tpl->assign("__redirect",pathos_flow_get());
	}
}

/*
 * Clear Cached Templates
 *
 * Clears all cached template data, either for all logged-in viewers,
 * or all viewers (anonymous and logged-in)
 *
 * @param constant $type One of the following:
 *	<br>SYS_TEMPLATE_CLEAR_ALL - To clear all cached templates
 *	<br>SYS_TEMPLATE_CLEAR_USERS - To clear all cached templates for individual sessions
 */
function pathos_template_clear($type = SYS_TEMPLATE_CLEAR_ALL) {
	if (DISPLAY_CACHE) {
		$s = new Smarty();
		if ($type == SYS_TEMPLATE_CLEAR_ALL) {
			$s->cache_dir = BASE."cache";
			$s->clear_all_cache();
			$__oldumask = umask(0);
			mkdir(BASE."cache/sessions",0777);
			umask($__oldumask);
			#chmod(BASE."cache/session",0777);
		} else {
			$s->cache_dir = BASE."cache/sessions";
			$s->clear_all_cache();
		}
	}
}

/*
 * Retrieve Module-Independent View File
 *
 * Looks in the theme and the /views directory for a .tpl file
 * corresponding to the passed view.  This is for resolving non-module
 * views.
 *
 * @param string $view The name of the view.
 *
 * @return string The filename of the view template, or "" if none found.
 */
function pathos_template_getViewFile($view) {
	if (is_readable(THEME_ABSOLUTE."views/$view.tpl")) {
		return THEME_ABSOLUTE."views/$view.tpl";
	} else if (is_readable(BASE."views/$view.tpl")) {
		return BASE."views/$view.tpl";
	} else {
		// Fall back to something that won't error.
		return TEMPLATE_FALLBACK_VIEW;
	}
}

function pathos_template_getViewConfigForm($module,$view,$form,$values) {
	$form_file = "";
	if (is_readable(BASE."themes/".DISPLAY_THEME."/modules/$module/views/$view.form")) $form_file = BASE."themes/".DISPLAY_THEME."/modules/$module/views/$view.form";
	else if (is_readable(BASE . "modules/$module/views/$view.form")) $form_file = BASE . "modules/$module/views/$view.form";
	else if ($view != DEFAULT_VIEW) {
		$view = DEFAULT_VIEW;
		if (is_readable(BASE."themes/".DISPLAY_THEME."/modules/$module/views/$view.form")) $form_file = BASE."themes/".DISPLAY_THEME."/modules/$module/views/$view.form";
		else if (is_readable(BASE . "modules/$module/views/$view.form")) $form_file = BASE . "modules/$module/views/$view.form";
	}
	
	if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
	pathos_forms_initialize();
	
	if ($form == null) $form = new form();
	if ($form_file == "") return $form;
	
	$form->register(null,"",new htmlcontrol("<hr size='1' /><b>Layout Configuration</b>"));
	
	$fh = fopen($form_file,"r");
	while (($control_data = fgetcsv($fh,65536,"\t")) !== false) {
		$data = array();
		foreach ($control_data as $d) {
			if ($d != "") $data[] = $d;
		}
		if (!isset($values[$data[0]])) $values[$data[0]] = 0;
		if ($data[2] == "checkbox") {
			$form->register("_viewconfig[".$data[0]."]",$data[1],new checkboxcontrol($values[$data[0]],true));
		} else if ($data[2] == 'text') {
			$form->register("_viewconfig[".$data[0]."]",$data[1],new textcontrol($values[$data[0]]));
		} else {
			$options = array_slice($data,3);
			$form->register("_viewconfig[".$data[0]."]",$data[1],new dropdowncontrol($values[$data[0]],$options));
		}
	}
	
	$form->register("submit","",new buttongroupcontrol("Save","","Cancel"));
	
	pathos_forms_cleanup();
	
	return $form;
}

function pathos_template_getViewConfigOptions($module,$view) {
// Can we simplify this?
	$form_file = "";
	if (is_readable(BASE."themes/".DISPLAY_THEME."/modules/$module/views/$view.form")) $form_file = BASE."themes/".DISPLAY_THEME."/modules/$module/views/$view.form";
	else if (is_readable(BASE . "modules/$module/views/$view.form")) $form_file = BASE . "modules/$module/views/$view.form";
	else if ($view != DEFAULT_VIEW) {
		$view = DEFAULT_VIEW;
		if (is_readable(BASE."themes/".DISPLAY_THEME."/modules/$module/views/$view.form")) $form_file = BASE."themes/".DISPLAY_THEME."/modules/$module/views/$view.form";
		else if (is_readable(BASE . "modules/$module/views/$view.form")) $form_file = BASE . "modules/$module/views/$view.form";
	}
	echo "Form File:$form_file:";
	if ($form_file == "") return array(); // no form file, no options
	
	$fh = fopen($form_file,"r");
	$options = array();
	while (($control_data = fgetcsv($fh,65536,"\t")) !== false) {
		$data = array();
		foreach ($control_data as $d) {
			if ($d != "") $data[] = $d;
		}
		$options[$data[0]] = $data[1];
	}
	return $options;
}

function pathos_template_getFormTemplates($type) {
	$forms = array();
	
	//Get the forms from the base form diretory
	if (is_dir(BASE.'forms/'.$type)) {
		if ($dh = opendir(BASE.'forms/'.$type)) {
			 while (false !== ($file = readdir($dh))) {
				if ( (substr($file,-4,4) == ".tpl") && ($file{0} != '_')) {
					$forms[substr($file,0,-4)] = substr($file,0,-4);
				}
			}
		}
	}
	//Get the forms from the themes form directory.  If the theme has forms of the same
	//name as the base form dir, then they will overwrite the ones already  in the array $forms.
	if (is_dir(THEME_ABSOLUTE.'forms/'.$type)) {
		if ($dh = opendir(THEME_ABSOLUTE.'forms/'.$type)) {
			 while (false !== ($file = readdir($dh))) {
				if ( (substr($file,-4,4) == ".tpl") && ($file{0} != '_')) {
					$forms[substr($file,0,-4)] = substr($file,0,-4);
				}
			}
		}
	}
	
	return $forms;
}

/* exdoc
 * Resolves a view name to a real .tpl file.
 * Consults both the theme and the standard views directory (in that order)
 * to determine where the template code for a view is stored.  Returns the absolute path
 * to the template file, or "" if no matching view file found.
 *
 * @node Subsystems:Template
 */
function pathos_template_getModuleViewFile($module,$view,$recurse = true) {
	$langdir = (LANG == 'en' ? '' : LANG . '/');
	if (is_readable(BASE."themes/".DISPLAY_THEME."/modules/$module/views/$langdir$view.tpl")) {
		// Try the language directory in theme
		return BASE."themes/".DISPLAY_THEME."/modules/$module/views/$langdir$view.tpl";
	}else if (is_readable(BASE . "modules/$module/views/$langdir$view.tpl")) {
		// Failing that, try the language directory in the module.
		return BASE . "modules/$module/views/$langdir$view.tpl";
	} else if (is_readable(BASE."themes/".DISPLAY_THEME."/modules/$module/views/$view.tpl")) {
		// Try the english directory in the theme.
		return BASE."themes/".DISPLAY_THEME."/modules/$module/views/$view.tpl";
	} else if (is_readable(BASE . "modules/$module/views/$view.tpl")) {
		// Failing even that, try the english directory in the module.
		return BASE . "modules/$module/views/$view.tpl";
	} else if ($recurse && $view != DEFAULT_VIEW) {
		// If we get here, try it with a different view.
		return pathos_template_getModuleViewFile($module,DEFAULT_VIEW);
	} else {
		// Something is really screwed up.
		// Fall back to something that won't error.
		return TEMPLATE_FALLBACK_VIEW;
	}
}


/* exdoc
 * Looks through the module's views directory and returns
 * all non-internal views that are found there.  The current theme
 * is not consulted.  Returns an array of all standard view names.
 * This array is unsorted.
 *
 * @param string $module The classname of the module to get views for.
 * @node Subsystems:Template
 */
function pathos_template_listModuleViews($module) {
	$views = array();
	$langdir = (LANG == 'en' ? '' : LANG.'/');
	if (is_readable(BASE."modules/$module/views/$langdir")) {
		$dh = opendir(BASE."modules/$module/views/$langdir");
		while (($file = readdir($dh)) !== false) {
			if (substr($file,-4,4) == ".tpl" && substr($file,0,1) != "_") $views[] = substr($file,0,-4);
		}
	}
	if (is_readable(THEME_BASE."modules/$module/views/$langdir")) {
		$dh = opendir(THEME_BASE."modules/$module/views/$langdir");
		while (($file = readdir($dh)) !== false) {
			if (substr($file,-4,4) == ".tpl" && substr($file,0,1) != "_") {
				$view = substr($file,0,-4);
				if (!in_array($view,$views)) $views[] = $view;
			}
		}
	}
	return $views;
}


?>