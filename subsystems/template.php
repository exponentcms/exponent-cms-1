<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
# Written and Designed by James Hunt
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

if (!defined('PATHOS')) exit('');

/* exdoc
 * The definition of this constant lets other parts of the system know 
 * that the subsystem has been included for use.
 * @node Subsystems:Template
 */
define('SYS_TEMPLATE',1);

define('TEMPLATE_FALLBACK_VIEW',BASE.'views/viewnotfound.tpl');

include_once(BASE.'external/Smarty/libs/Smarty.class.php');

class basetemplate {
	// Smarty template object.
	var $tpl;
	
	// The full server-side filename of the .tpl file being used.
	// This will be used by modules on the outside, for retrieving view configs.
	var $viewfile = "";
	
	// Name of the view (for instance, 'Default' for 'Default.tpl')
	var $view = "";
	
	// Full server-side directory path of the .tpl file being used.
	var $viewdir = "";
	
	//fix for the wamp/lamp issue
	var $langdir = "";
	//	
	
	/*
	 * Assign a variable to the template.
	 *
	 * @param string $var The name of the variable - how it will be referenced inside the Smarty code
	 * @param mixed $val The value of the variable.
	 */
	function assign($var,$val) {
		$this->tpl->assign($var,$val);
	}
	
	/*
	 * Render the template and echo it to the screen.
	 */
	function output() {
		// Load language constants
		//$this->tpl->assign('_TR',pathos_lang_loadFile($this->viewdir.'/'.$this->view.'.php')); //fix lamp issue
		$this->tpl->assign('_TR',pathos_lang_loadFile($this->langdir."".$this->view.'.php')); //fix lamp issue
		
		$this->tpl->display($this->view.'.tpl');
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
		$this->tpl->assign('permissions',$permissions_register);
	}
	
	/*
	 * Render the template and return the result to the caller.
	 */
	function render() { // Caching support?
		// Load language constants
		$this->tpl->assign('_TR',pathos_lang_loadFile($this->viewdir.'/'.$this->view.'.php'));
		return $this->tpl->fetch($this->view.'.tpl');
	}
}
/*
 * Wraps the template system in use, to provide a uniform and consistent
 * interface to templates.
 */
class template extends basetemplate {	
	var $module = '';
	
	function template($module,$view = null,$loc=null,$caching=false) {
		// Set up the Smarty template variable we wrap around.
		$this->tpl = new Smarty();
		$this->tpl->php_handling = SMARTY_PHP_REMOVE;
		$this->tpl->plugins_dir[] = BASE.'plugins';
		
		$this->viewfile = pathos_template_getModuleViewFile($module,$view);
		$this->viewparams = pathos_template_getViewParams($this->viewfile);
		$this->viewdir = str_replace(BASE,'',realpath(dirname($this->viewfile)));
		
		$this->view = substr(basename($this->viewfile),0,-4);
		$this->tpl->template_dir = $this->viewdir;
		
		//fix for the wamp/lamp issue
		$this->langdir = "modules/".$module."/views/";
		//
		
		// Make way for i18n
		// $this->tpl->compile_dir = $this->viewdir."_c";
		$this->tpl->compile_dir = BASE.'/views_c';
		$this->tpl->compile_id = md5($this->viewfile);
		
		$expected_view = ($this->viewfile == TEMPLATE_FALLBACK_VIEW ? $view : $this->view);
		
		$this->tpl->assign("__view",$expected_view);
		if ($loc == null) $loc = pathos_core_makeLocation($module);
		$this->tpl->assign("__loc",$loc);
		$this->tpl->assign("__redirect",pathos_flow_get());
		
		// View Config
		global $db;
		$container = $db->selectObject("container","internal='".serialize($loc)."'");
		$this->viewconfig = ($container && $container->view_data != "" ? unserialize($container->view_data) : array());
		$this->tpl->assign("__viewconfig",$this->viewconfig);
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
	
		$langdir = (LANG == 'en' ? '' : LANG.'/');
	
		$this->tpl = new Smarty();
		$this->tpl->php_handling = SMARTY_PHP_REMOVE;
		$this->tpl->plugins_dir[] = BASE."plugins";
		
		if (is_readable(THEME_ABSOLUTE."forms/".$formtype."/".$langdir.$view.".tpl")) {
			$this->viewfile = THEME_ABSOLUTE."forms/".$formtype."/".$langdir.$view.".tpl";
		} else if (is_readable(THEME_ABSOLUTE."forms/".$formtype."/".$view.".tpl")) {
			$this->viewfile = THEME_ABSOLUTE."forms/".$formtype."/".$view.".tpl";
		} else if (is_readable(BASE."forms/".$formtype."/".$langdir.$view.".tpl")) {
			$this->viewfile = BASE."forms/".$formtype."/".$langdir.$view.".tpl";
		} else if (is_readable(BASE."forms/".$formtype."/".$view.".tpl")) {
			$this->viewfile = BASE."forms/".$formtype."/".$view.".tpl";
		} else {
			$this->viewfile = TEMPLATE_FALLBACK_VIEW;
		}
		
		$this->view = substr(basename($this->viewfile),0,-4);
		$this->viewdir = realpath(dirname($this->viewfile));
		
		$this->tpl->template_dir = $this->viewdir;
		$this->tpl->compile_dir = BASE.'views_c';
		
		$this->tpl->compile_id = md5($this->viewfile);
		
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
		// Make way for i18n
		// $this->tpl->compile_dir = $this->viewdir."_c";
		$this->tpl->compile_dir = BASE.'/views_c';
		$this->tpl->compile_id = md5($this->viewfile);
		
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
		$this->viewdir = str_replace(BASE,'',realpath(dirname($file)));
		
		$this->tpl->template_dir = $this->viewdir;
		// Make way for i18n
		// $this->tpl->compile_dir = $this->viewdir."_c";
		$this->tpl->compile_dir = BASE.'/views_c';
		$this->tpl->compile_id = md5($this->viewfile);
		
		$this->tpl->assign("__view",$view);
		$this->tpl->assign("__redirect",pathos_flow_get());
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
	$langdir = (LANG == 'en' ? '' : LANG.'/');
	if (is_readable(THEME_ABSOLUTE."views/$langdir$view.tpl")) {
		return THEME_ABSOLUTE."views/$langdir$view.tpl";
	} else if (is_readable(THEME_ABSOLUTE."views/$view.tpl")) {
		return THEME_ABSOLUTE."views/$view.tpl";
	} else if (is_readable(BASE."views/$langdir$view.tpl")) {
		return BASE."views/$langdir$view.tpl";
	} else if (is_readable(BASE."views/$view.tpl")) {
		return BASE."views/$view.tpl";
	} else {
		// Fall back to something that won't error.
		return TEMPLATE_FALLBACK_VIEW;
	}
}


// I thing these still need to be i18n-ized
function pathos_template_getViewConfigForm($module,$view,$form,$values) {
	$form_file = "";
	if (is_readable(BASE."themes/".DISPLAY_THEME."/modules/$module/views/$view.form")) $form_file = BASE."themes/".DISPLAY_THEME."/modules/$module/views/$view.form";
	else if (is_readable(BASE . "modules/$module/views/$view.form")) $form_file = BASE . "modules/$module/views/$view.form";
	else if ($view != DEFAULT_VIEW) {
		$view = DEFAULT_VIEW;
		if (is_readable(BASE."themes/".DISPLAY_THEME."/modules/$module/views/$view.form")) $form_file = BASE."themes/".DISPLAY_THEME."/modules/$module/views/$view.form";
		else if (is_readable(BASE . "modules/$module/views/$view.form")) $form_file = BASE . "modules/$module/views/$view.form";
	}
	
	if (!defined("SYS_FORMS")) require_once(BASE."subsystems/forms.php");
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
function pathos_template_listModuleViews($module,$lang = LANG) {
	$views = array();
	$langdir = ($lang == 'en' ? '' : $lang.'/');
	if (is_readable(BASE."modules/$module/views/$langdir")) {
		$dh = opendir(BASE."modules/$module/views/$langdir");
		while (($file = readdir($dh)) !== false) {
			if (substr($file,-4,4) == ".tpl" && substr($file,0,1) != "_") $views[] = substr($file,0,-4);
		}
	}
	if (is_readable(THEME_ABSOLUTE."modules/$module/views/$langdir")) {
		$dh = opendir(THEME_ABSOLUTE."modules/$module/views/$langdir");
		while (($file = readdir($dh)) !== false) {
			if (substr($file,-4,4) == ".tpl" && substr($file,0,1) != "_") {
				$view = substr($file,0,-4);
				if (!in_array($view,$views)) $views[] = $view;
			}
		}
	}
	if (!count($views) && $lang != 'en') {
		return pathos_template_listModuleViews($module,'en');
	}
	return $views;
}

function pathos_template_getViewParams($viewfile) {
	$base = substr($viewfile,0,-4);
	$vparam = null;
	if (is_readable($base.'.config')) {
		$vparam = include($base.'.config');
	}
	return $vparam;
}


?>