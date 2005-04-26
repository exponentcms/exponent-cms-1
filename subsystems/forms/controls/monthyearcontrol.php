<?php

##################################################
# __XYZZY_BOILER
# $Id$
##################################################

/**
 * Month Year Picker
 *
 * @author Greg Otte
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
include_once(BASE."subsystems/forms/controls/formcontrol.php");

/**
 * Month Year Picker
 *
 * @package Subsystems
 * @subpackage Forms
 */
class monthyearcontrol extends formcontrol {
	
	function monthyearcontrol($default_month = null,$default_year = null) {
		if ($default_month == null) date("m");
		if ($default_year == null) date("Y");
		$this->default_month = $default_month;
		$this->default_year = $default_year;
	}
	
	function controlToHTML($name) {
		$html = '<select id="' . $name . '_month" name="' . $name . '_month">';
		for ($i = 1; $i <= 12; $i++) {
			$s = ((strlen($i) == 1)?"0".$i:$i);
			$html .= '<option value="' . $s . '"';
			if ($s == $this->default_month) $html .= " selected";
			$html .= '>' . $s . '</option>';
		}
		$html .= '</select>';
		$html .= "/";
		$html .= '<select id="' . $name . '_year" name="' . $name . '_year">';
		for ($i = date("Y"); $i <= (date("Y") + 15); $i++) {
			$html .= '<option value="' . $i . '"';
			if ($i == $this->default_year) $html .= " selected";
			$html .= '>' . $i . '</option>';
		}
		$html .= '</select>';
		return $html;
	}
}

?>
