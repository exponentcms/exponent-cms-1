<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Written and Designed by Adam Kessler
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

function renderAction(array $parms=array()) {
	//Get some info about the controller
	$baseControllerName = $parms['controller'];
	$fullControllerName = $parms['controller'].'Controller';
	$controllerClass = new ReflectionClass($fullControllerName);

	// Figure out the action to use...if the specified action doesn't exist then
	// we look for the index action.  If that isn't available then we fallback to the 
	// showall action.
	if ($controllerClass->hasMethod($parms['action'])) {
		$action = $parms['action'];
	} elseif ($controllerClass->hasMethod('index')) {
		$action = 'index';
	} else {
		$action = 'showall';
	}

	//Set up the template to use for this action
	global $template;
        $template = get_template_for_action($baseControllerName, $action);

        $controller = new $fullControllerName;
        $models = isset($controller->models) ? $controller->models : array($baseControllerName);
        foreach ($models as $model) {
        	$controller->$model = new $model();
        }
	
	$controller->params = $parms;
	$controller->$action();
	$template->assign('flash', exponent_sessions_get('flash'));

	// This is where we set the module/instance id - sadly, for now this has to be passed around all over the place.
	$instance = isset($parms['instance']) ? $parms['instance'] : $_REQUEST['instance'];
	$template->assign('instance', $instance);

        $html = $template->output();
	flushFlash();
	return $html;
}

function hotspot($source = null) {
	if (!empty($source)) {
		global $sectionObj;
                $page = new page($sectionObj->id);
		$modules = $page->getModulesBySource($source);
                //eDebug($modules);exit();

                foreach ($modules as $module) {
                	renderAction(array('controller'=>$module->type, 'action'=>$module->action, 'instance'=>$module->id));
                }
	}
}

function redirect_to(array $parms=array()) {
	if (count($parms) == 0) return false;

	$url = '';
	if (isset($parms['url'])) {
		$url = $parms['url'];
	} else {
		$url  = URL_FULL;
		$url .= isset($parms['controller']) ? $parms['controller'] : $_REQUEST['controller'];  // Get or guess which controller to redirect to
		$url .= '/';
		$url .= isset($parms['action']) ? $parms['action'] : 'index';  //get or guess the action to redirect to
		$url .= '/';
		eDebug($parms);
		foreach ($parms as $var=>$val) {
			if ($var != 'controller' && $var != 'action') $url .= $var.'/'.$val.'/';
		}
		
	}
	
	//echo $url;
	//exit();
	header("Location: " . urldecode($url));
}	

function flash($name="", $msg) {
	if ($name == "") return false;
	$flash = exponent_sessions_get('flash');
	$flash[$name] = $msg;
	exponent_sessions_set('flash', $flash);
}

function flushFlash() {
	exponent_sessions_set('flash', array());
}

function assign_to_template(array $vars=array()) {
	if (count($vars) == 0) return false;

	global $template;
	foreach ($vars as $key=>$val) {
		$template->assign($key, $val);
	}
}

function get_model_for_controller($controller_name) {
	$start_pos = stripos($controller_name, 'controller');
	if ($start_pos === false) {
		return false;
	} else {
		return substr($controller_name, 0, $start_pos);
	}
}

function get_template_for_action($controller, $action) {
        $loc->mod = $controller;
        $loc->src = '';
        $loc->int = '';
	
	if (file_exists(BASE.'views/'.$controller.'/'.$action.'.tpl')) {
		return new template($controller, $action, $loc, false, 'controllers');
	} elseif (file_exists(BASE.'views/scaffold/'.$action.'.tpl')) {
		return new template('scaffold', $action, $loc, false, 'controllers');
	} else {
		return new template('scaffold', 'blank', $loc, false, 'controllers');
	}
	
}

?>
