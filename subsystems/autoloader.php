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

if (!defined('PATHOS')) exit('');

if (phpversion() >= 5) {
	/* exdoc
	 * The definition of this constant lets other parts
	 * of the system know that the AutoLoader Subsystem
	 * has been included for use.
	 * @node Subsystems:Autoloader
	 */
	define("SYS_AUTOLOADER",1);
	
	/* exdoc
	 * In PHP5, the autoloader function will check these
	 * directories when it tries to load a class definition
	 * file.  Other parts of the system should append to this
	 * directory as needed, in order to take full advantage
	 * of autoloading
	 * @node Subsystems:Autoloader
	 */
	$auto_dirs = array("datatypes"=>BASE."datatypes");
	
	/* exdoc
	 * This function overrides the default PHP5 autoloader,
	 * and instead looks at the $AutoLoadDirs global to look
	 * for class files.  This function is automatically
	 * invoked in PHP5
	 *
	 * @param	string $class The name of the class to look for.
	 * @node Subsystems:Autoloader
	 */
	function __autoload($class) {
		global $auto_dirs;
		foreach ($auto_dirs as $auto_dir) {
			if (is_readable("$auto_dir/$class.php")) {
				include_once("$auto_dir/$class.php");
				return;
			}
		}
	}
} else {
	define("SYS_AUTOLOADER",2);
	if (is_readable(BASE."datatypes")) {
		$dh = opendir(BASE."datatypes");
		while (($file = readdir($dh)) !== false) {
			if (is_readable(BASE."datatypes/$file") && substr($file,-4,4) == ".php") {
				include_once(BASE."datatypes/$file");
			}
		}
	}
}

?>