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
 * @node Subsystems:Javascript
 */
define("SYS_JAVASCRIPT",1);

/* exdoc
 * Takes a stdClass object from PHP, and generates the
 * corresponding Javascript class function.  The data in the
 * members of the PHP object is not important, only the
 * presence and names of said members.  Returns the
 * javascript class function code.
 *
 * @param Object $object The object to translate
 * @param string $name What to call the class in javascript
 * @node Subsystems:Javascript
 */
function pathos_javascript_class($object, $name) {
	$otherclasses = array();
	$js = "function $name(";
	$js1 = "";
	foreach (get_object_vars($object) as $var=>$val) {
		$js .= "var_$var,";
		$js1 .= "\tthis.var_$var = var_$var;\n";
		if (is_object($val)) {
			$otherclasses[] = array($name."_".$var,$val);
		}
	}
	$js = substr($js,0,-1) . ") {\n" . $js1 . "}\n";
	foreach ($otherclasses as $other) {
		echo "/// Other Object : ".$other[1] . "," . $other[0] ."\n";
		$js .= "\n".pathos_javascript_class($other[1],$other[0]);
	}
	return $js;
}

/* exdoc
 * Takes a stdClass object from PHP, and generates the
 * corresponding Javascript calls to make a new Javascript
 * object.  In order for the resulting Javascript to function
 * properly, a call to pathos_javascript_class must have been
 * made previously, and the same $name attribute used. Returns
 * the javascript code to create a new object.
 *
 * The data in the members of the PHP object will be used to
 * populate the members of the new Javascript object.
 *
 * @param Object $object The object to translate
 * @param string $name The name of the javascript class
 * @node Subsystems:Javascript
 */
function pathos_javascript_object($object, $name) {
	$js = "new $name(";
	foreach (get_object_vars($object) as $var=>$val) {
		if (is_string($val)) $js .= "'".str_replace("'","&apos;",$val)."',";
		else if (is_array($val)) $js .= pathos_javascript_array($val) . ",";
		else if (is_object($val)) $js .= pathos_javascript_object($val,$name."_".$var) . ",";
		else $js .= "$val,";
	}
	return substr($js,0,-1) . ")";
}

/* exdoc
 * Generates the Javascript code to instantiate an array
 * identical to the passed array.  Returns The javascript code
 * to create and populate the array in javascript.
 *
 * @param $array The PHP array to translate
 * @node Subsystems:Javascript
 */
function pathos_javascript_array($array) {
	$js = "new Array( ";
	foreach ($array as $val) {
		if (is_string($val)) $js .= "'".str_replace("'","&apos;",$val)."',";
		else if (is_array($val)) $js .= pathos_javascript_array($val) . ",";
		else if (is_object($val)) $js .= pathos_javascript_object($val,$var) . ",";
		else $js .= "$val,";
	}
	return substr($js,0,-1) . ")";
}

?>