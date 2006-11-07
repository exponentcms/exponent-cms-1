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
		
		$PATH_TO_INCs = BASE . "external/editors/";

		if(is_readable($PATH_TO_INCs . SITE_WYSIWYG_EDITOR . '.glue')){
			ob_start();
				global $db;
				if($this->toolbar == "") {
					$config = $db->selectObject("toolbar_" . SITE_WYSIWYG_EDITOR, "active=1");
				}else{
					$config = $db->selectObject("toolbar_" . SITE_WYSIWYG_EDITOR, "name='" . $this->toolbar . "'");
				}
				if ($config) {
					echo '<script language="javascript">/* <![CDATA[ */' . "\n";
					echo "		Exponent.WYSIWYG_toolbar = " . $config->data . ";\n";
					echo '/* ]]> */</script>' . "\n";
				}

				include($PATH_TO_INCs . SITE_WYSIWYG_EDITOR . '.glue');
			$html = ob_get_contents();
			ob_end_clean();	
			return $html;
		}
		else {
			echo "Sorry, the " . SITE_WYSIWYG_EDITOR . " WYSIWYG Editor is not installed";
			//TODO: handle fallback to <htmlarea> here, and dispatch message to regular error channel
		}
	}
	
	function parseData($name, $values, $for_db = false) {
		$html = $values[$name];
		if (trim($html) == "<br />") $html = "";
		return $html;
	}
}

?>
