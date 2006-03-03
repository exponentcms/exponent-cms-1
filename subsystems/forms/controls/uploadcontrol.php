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

if (!defined('EXPONENT')) exit('');

/**
 * Upload Control
 *
 * @author James Hunt
 * @copyright 2004 James Hunt and the OIC Group, Inc.
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
 * Upload Control
 *
 * @package Subsystems
 * @subpackage Forms
 */
class uploadcontrol extends formcontrol {
	
	function name() { return "File Upload Field"; }
	
	function uploadcontrol($default = "", $disabled = false) {
		$this->disabled = $disabled;
	}
	
	function onRegister(&$form) {
		$form->enctype = "multipart/form-data";
	}

	function controlToHTML($name) {
		$html = "<input type=\"file\" name=\"$name\" ";
		$html .= ($this->disabled?"disabled ":"");
		$html .= ($this->tabindex>=0?"tabindex=\"".$this->tabindex."\" ":"");
		$html .= ($this->accesskey != ""?"accesskey=\"".$this->accesskey."\" ":"");
		$html .= "/>";
		return $html;
	}
}

?>
