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
 * Password Control
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
 * Password Control
 *
 * @package Subsystems
 * @subpackage Forms
 */
class passwordcontrol extends formcontrol {
	var $default = "";
	var $size = 0;
	var $maxlength = "";
	
	function name() { return "Password Field"; }

	function passwordcontrol($default = "", $size = 0, $disabled = false, $maxlength = 0) {
		$this->default = $default;
		$this->size = $size;
		$this->disabled = $disabled;
		$this->maxlength = $maxlength;
	}
	
	function controlToHTML($name) {
		$html = "<input type=\"password\" name=\"$name\" value=\"" . $this->default . "\" ";
		$html .= ($this->size?"size=\"".$this->size."\" ":"");
		$html .= ($this->disabled?"disabled ":"");
		$html .= ($this->maxlength?"maxlength=\"".$this->maxlength."\" ":"");
		$html .= ($this->tabindex >= 0?"tabindex=\"".$this->tabindex."\" ":"");
		$html .= ($this->accesskey != ""?"accesskey=\"".$this->accesskey."\" ":"");
		$html .= "/>";
		return $html;
	}
	
	function form($object) {

	}
	
	function update($values, $object) {

	}
	
	
	
	
}

?>
