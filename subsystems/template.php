<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Copyright (c) 2006 Maxim Mueller
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

if (!defined('EXPONENT')) exit('');

/* exdoc
 * The definition of this constant lets other parts of the system know 
 * that the subsystem has been included for use.
 * @node Subsystems:Template
 */
define('SYS_TEMPLATE',1);

define('TEMPLATE_FALLBACK_VIEW', BASE.'views/viewnotfound.tpl');

class BaseTemplate {
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
	
	//PHP5 constructor
	function __construct($item_type, $item_dir, $view = "Default") {
		
		include_once(BASE.'external/Smarty/libs/Smarty.class.php');
		
		// Set up the Smarty template variable we wrap around.
		$this->tpl = new Smarty();
		//Some (crappy) wysiwyg editors use php as their default initializer
		//$this->tpl->php_handling = SMARTY_PHP_REMOVE;
		$this->tpl->plugins_dir[] = BASE . 'subsystems/template/Smarty/plugins';
		
		//autoload filters
		$this->tpl->autoload_filters = array('post' => array('includeMiscFiles'));
		
		$this->viewfile = exponent_template_getViewFile($item_type, $item_dir, $view);
		$this->viewdir = realpath(dirname($this->viewfile));
				
		$this->view = substr(basename($this->viewfile),0,-4);
		
		//fix for the wamp/lamp issue
		$this->langdir = $item_type . "/" . $item_dir . "/views/";
		
		
		$this->tpl->template_dir = $this->viewdir;
		
		$this->tpl->compile_dir = BASE.'/tmp/views_c';
		$this->tpl->compile_id = md5($this->viewfile);
		
		$this->tpl->assign("__view", $this->view);
		$this->tpl->assign("__redirect", exponent_flow_get());
		
		// Load language constants
		$this->tpl->assign('_TR',exponent_lang_loadFile($this->langdir . $this->view . '.php'));
	}
	
	//PHP4: compatibility wrapper
	function BaseTemplate($item_type, $item_dir, $view = "Default") {
		//call PHP5 constructor
		$this->__construct($item_type, $item_dir, $view);
	}
	
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
		$this->tpl->display($this->view.'.tpl');
	}
	
	function register_permissions($perms,$locs) {
		$permissions_register = array();
		if (!is_array($perms)) $perms = array($perms);
		if (!is_array($locs)) $locs = array($locs);
		foreach ($perms as $perm) {
			foreach ($locs as $loc) {
				$permissions_register[$perm] = (exponent_permissions_check($perm,$loc) ? 1 : 0);
			}
		}
		$this->tpl->assign('permissions', $permissions_register);
	}
	
	/*
	 * Render the template and return the result to the caller.
	 */
	function render() {
		// Caching support?
		return $this->tpl->fetch($this->view.'.tpl');
	}
}
/*
 * Wraps the template system in use, to provide a uniform and consistent
 * interface to templates.
 */
//TODO: prepare this class for multiple template systems
class template extends BaseTemplate {
		
	var $module = '';
	
	//PHP5 constructor
	function __construct($module, $view = null, $loc=null, $caching=false) {
		parent::__construct("modules", $module, $view);
		
		$this->viewparams = exponent_template_getViewParams($this->viewfile);
								
		if ($loc == null) {
			$loc = exponent_core_makeLocation($module);
		}
		
		$this->tpl->assign("__loc",$loc);
		$this->tpl->assign("__name", $module);
		
		// View Config
		global $db;
		$container_key = serialize($loc);
		$cache = exponent_sessions_getCacheValue('containermodule');
		if (isset($cache[$container_key])){
			$container = $cache[$container_key];
		}else{
			$container = $db->selectObject("container","internal='".$container_key."'");
			$cache[$container_key] = $container;
		}
		$this->viewconfig = ($container && $container->view_data != "" ? unserialize($container->view_data) : array());
		$this->tpl->assign("__viewconfig",$this->viewconfig);
		//echo "<xmp>";
		//print_r($this);
		//echo "</xmp>";
	}	
	
	//PHP4: compatibility wrapper
	function template($module, $view = null, $loc=null, $caching=false) {
		$this->__construct($module, $view, $loc, $caching);
	}
	
	
}

/* exdoc
 * 
 * Control Template wrapper
 * 
 * 
 */
class ControlTemplate extends basetemplate {
	
	var $viewitem = "";
	
	//PHP5 constructor
	function __construct($control, $view = "Default", $loc = null) {
		parent::__construct("controls", $control, $view, $loc);
		
		$this->tpl->assign("__name", $control);
	}
	
	function ControlTemplate($control, $view = "Default", $loc = null) {
		//PHP4: compatibility wrapper 
		$this->__construct($control, $view, $loc);
	}
	
	/*
	 * Render the template and return the result to the caller.
	 * temporary override for testing functionality
	 */
	function render() {
		//pump the viewitem into the view layer
		
		$this->tpl->assign("vi", $this->viewitem);
		$this->tpl->assign("dm", $this->viewitem->datamodel);
		
		//call childobjects show() method recursively, based on render depth setting
		//assign output
		
		return $this->tpl->fetch($this->view.'.tpl');
	}
}

/*
 * Form Template Wrapper
 *
 * This class is used for site wide forms.  
 *
 * @package Subsystems
 * @subpackage Template
 */
class formtemplate extends BaseTemplate {
	
	//PHP5 constructor
	function __construct($form, $view) {
		parent::__construct("forms", $form, $view);
		
		$this->tpl->assign("__name", $form);
	}
	
	//PHP4: compatibility wrapper
	function formtemplate($form, $view) {
		$this->__construct($form, $view);
	}
}

class filetemplate extends BaseTemplate {
	
	//PHP5 constructor
	function __construct($file) {
		parent::__construct("", "", $file);
	}
	
	//PHP4: compatibility wrapper
	function filetemplate($file) {
		$this->__construct($file);
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
class standalonetemplate extends BaseTemplate {
		
	//PHP5 constructor
	function __construct($view) {
		parent::__construct("", "", $view);
	}
	
	//PHP4: compatibility wrapper
	function standalonetemplate($view) {
		$this->__construct($view);
	}
}

/*
 * Retrieve View File
 *
 * Looks in the theme and the /views directory for a .tpl file
 * corresponding to the passed view.
 *
 * @param string $type One of "modules"", "controls"", "forms" or ""
 * @param string $name The name the object we are requesting a view from
 * @param string $view The name of the requested view
 *
 * @return string The full filepath of the view template
 */
function exponent_template_getViewFile($type="", $name="", $view) {
	$viewfilepath = exponent_core_resolveFilePaths($type, $name, "tpl", $view);
	if ($viewfilepath != false) {
		return array_shift($viewfilepath);
	} else if ($view != DEFAULT_VIEW) {
		// If we get here, try it with a different view.
		return exponent_template_getViewFile($type, $name, DEFAULT_VIEW);
	} else {
		// Something is really screwed up.
		// Fall back to something that won't error.
		return TEMPLATE_FALLBACK_VIEW;
	}
}

//DEPRECATED: backward compatibility wrapper
function exponent_template_getModuleViewFile($name, $view, $recurse=true) {
	return exponent_template_getViewFile("modules", $name, $view);
}

// I think these still need to be i18n-ized
function exponent_template_getViewConfigForm($module,$view,$form,$values) {
	$form_file = "";
	$filepath = array_shift(exponent_core_resolveFilePaths("modules", $module , "form" , $view));
	if ($filepath != false) {
		$form_file = $filepath;
	}
	
	if (!defined("SYS_FORMS")) require_once(BASE."subsystems/forms.php");
	exponent_forms_initialize();
	
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

function exponent_template_getViewConfigOptions($module,$view) {
	$form_file = "";
	$filepath = array_shift(exponent_core_resolveFilePaths("modules", $module, "form", $view));
	if ($filepath != false) {
		$form_file = $filepath;
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

function exponent_template_listFormTemplates($type) {
	return exponent_core_buildNameList("forms", $type, "tpl", "[!_]*");
}


/* exdoc
 * 
 * Looks through the module's views directory and returns
 * all non-internal views that are found there.
 * Returns an array of all standard view names.
 * This array is unsorted.
 *
 * @param string $module The classname of the module to get views for.
 * @param string $lang deprecated, was used to list language specific templates
 * @node Subsystems:Template
 */
function exponent_template_listModuleViews($module, $lang = LANG) {
	return exponent_core_buildNameList("modules", $module, "tpl", "[!_]*");

}

function exponent_template_getViewParams($viewfile) {
	$base = substr($viewfile,0,-4);
	$vparam = null;
	if (is_readable($base.'.config')) {
		$vparam = include($base.'.config');
	}
	return $vparam;
}


?>