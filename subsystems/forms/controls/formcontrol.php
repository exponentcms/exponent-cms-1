<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
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
 * Basic Form Control
 *
 * @author James Hunt
 * @copyright 2004-2006 OIC Group, Inc.
 * @version 0.95
 *
 * @package Subsystems
 * @subpackage Forms
 */

/**
 * Basic Form Control
 *
 * @package Subsystems
 * @subpackage Forms
 */
class formcontrol {
	var $accesskey = "";
	var $default = "";
	var $disabled = false;
	var $tabindex = -1;
	var $inError = 0; // This will ONLY be set by the parent form.

	function name() { return "formcontrol"; }
	function isSimpleControl() { return false; }
	function getFieldDefinition() { return array(); }

	function toHTML($label,$name) {
		$this->id  = (empty($this->id)) ? $name : $this->id;
		$html = "<div id=\"".$this->id."Control\" class=\"control";
		$html .= (!empty($this->required)) ? ' required">' : '">';
		$html .= "<label>";
		if(empty($this->flip)){
			$html .= "<span class=\"label\">".$label."</span>";
			$html .= $this->controlToHTML($name);
		} else {
			$html .= $this->controlToHTML($name);
			$html .= "<span class=\"label\">".$label."</span>";
		}
		$html .= "</label>";
		$html .= "</div>";			
		return $html;
	}
	
	function controlToHTML($name) {
		return "";
	}
	
	function parseData($original_name,$formvalues) {
		return (isset($formvalues[$original_name])?$formvalues[$original_name]:"");
	}
	
	function onUnRegister(&$form) { // Do we need the explicit ref op??
		return true;
	}
	
	function onRegister(&$form) { // Do we need the explicit ref op??
		return true;
	}
	
	function templateFormat($db_data, $ctl = null) {
		return isset($db_data)?$db_data:"";
	}
}

?>
