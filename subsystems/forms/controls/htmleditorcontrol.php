<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Copyright 2006-2007 Maxim Mueller
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

/**
 * HTML Editor Control
 *
 * @author James Hunt
 * @copyright 2004-2006 OIC Group, Inc.
 * @version 0.95
 *
 * @package Subsystems
 * @subpackage Forms
 */

/**
 * Manually include the class file for formcontrol, for PHP4
 * (This does not adversely affect PHP5)
 */
require_once(BASE."subsystems/forms/controls/formcontrol.php");

/**
 * HTML Editor Control
 *
 * @package Subsystems
 * @subpackage Forms
 */
class htmleditorcontrol extends formcontrol {
	var $module = "";
	var $toolbar = "";
	
	
	function name() {
		return "WYSIWYG Editor";
	}
	
	
	function htmleditorcontrol($default="",$module = "",$rows = 20,$cols = 60, $toolbar = "") {
		$this->default = $default;
		$this->module = $module; // For looking up templates.
		$this->toolbar = $toolbar;
	}
	
	
	function controlToHTML($name) {

			global $db;
			if($this->toolbar == "") {
				$config = $db->selectObject("toolbar_" . SITE_WYSIWYG_EDITOR, "active=1");
			}else{
				$config = $db->selectObject("toolbar_" . SITE_WYSIWYG_EDITOR, "name='" . $this->toolbar . "'");
			}
			
			//as long as we don't have proper datamodels, emulate them
			//there are at least two sets of data: view data and content data
			$view = new StdClass();
			$content = new StdClass();
			
			$view->toolbar = $config;
			$view->path_to_editor = PATH_RELATIVE . "external/editors/" . SITE_WYSIWYG_EDITOR . "/";
			$view->init_done = false;
			
			$content->name = $name;
			$content->value = $this->default;
			
			//create new view object
			$viewObj = new ControlTemplate("EditorControl", SITE_WYSIWYG_EDITOR);
			
			//assign the data models to the view object
			$viewObj->assign("view", $view);
			$viewObj->assign("content", $content);
			
			//return the processed template to the caller for display
			return $viewObj->render();
	}
	
	
	function parseData($name, $values, $for_db = false) {
		$html = $values[$name];
		if (trim($html) == "<br />") $html = "";
		return $html;
	}
}

?>
