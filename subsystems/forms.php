<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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
//GREP:HARDCODEDTEXT

/* exdoc
 * The definition of this constant lets other parts
 * of the system know that the Forms Subsystem
 * has been included for use.
 *
 * @node Subsystems:Forms
 */
define("SYS_FORMS",1);

/* exdoc
 * Initialize the Subsystem
 *
 * This function includes files that would not otherwise be needed,
 * but are required for the Forms Subsystem to function properly.
 *
 * For servers running PHP5, this function registers the form class
 * file directories with the AutoLoader subsystem.
 *
 * For servers running PHP4, initializing the Forms Subsystem
 * can be a bit time-consuming, although testing and profiling done
 * justifies the existence of the subsystem.  The overhead is barely
 * noticeable, but it is there.
 *
 * @node Subsystems:Forms
 */
function pathos_forms_initialize() {
	$forms_dir = BASE."subsystems/forms";
	$controls_dir = BASE."subsystems/forms/controls";
	if (phpversion() < 5) {
		if (is_readable($forms_dir)) {
			$dh = opendir($forms_dir);
			while (($file = readdir($dh)) !== false) {
				if (is_readable("$forms_dir/$file") && substr($file,-4,4) == ".php") {
					include_once("$forms_dir/$file");
				}
			}
		} else echo "form directory not readable.";
		if (is_readable($controls_dir)) {
			$dh = opendir($controls_dir);
			while (($file = readdir($dh)) !== false) {
				if (is_readable("$controls_dir/$file") && substr($file,-4,4) == ".php") {
					include_once("$controls_dir/$file");
				}
			}
		} else echo "control directory not readable.";
	} else {
		if (is_readable($controls_dir)) {
			global $auto_dirs;
			$auto_dirs["forms_forms"] = $forms_dir;
			$auto_dirs["forms_controls"] = $controls_dir;
		} else echo "control directory not readable.";
	}
}

/* exdoc
 * This function complements pathos_forms_cleanup, by properly
 * cleaning up AutoLoader modifications made by the initialization.
 *
 * While this only benefits servers running PHP5, it does not adversely
 * affect PHP4 servers.  For best practices, always call pathos_forms_cleanup
 * if you have called pathos_forms_initialize.
 *
 * @node Subsystems:Forms
 */
function pathos_forms_cleanup() {
/*
	if (phpversion() >= 5) {
		global $auto_dirs;
		if (isset($auto_dirs["forms_forms"])) unset($auto_dirs["forms_forms"]);
		if (isset($auto_dirs["forms_controls"])) unset($auto_dirs["forms_controls"]);
	}
*/
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_forms_listControlTypes() {
	$cdh = opendir(BASE."subsystems/forms/controls");
	$list = array();
	while (($ctl = readdir($cdh)) !== false) {
		if (substr($ctl,-4,4) == ".php" && is_readable(BASE."subsystems/forms/controls/$ctl")) {
			if (call_user_func(array(substr($ctl,0,-4),"isSimpleControl"))) {
				$list[substr($ctl,0,-4)] = call_user_func(array(substr($ctl,0,-4),"name"));
			}
			#$list[substr($ctl,0,-4)] = $ctl;
		}
	}
	return $list;
}

?>