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

/**
 * Load Configuration Files
 *
 * Includes all configuration files for the site.
 * User-stored configuration resides in the conf/config.php
 * file.  However, to make things work smoothly, different parts
 * of the system can provide defaults, in case they are installed
 * after the site has been configured.  This file looks through the
 * conf/extensions directory and includes *.default.php files.
 *
 * @author James Hunt
 * @copyright 2004 James Hunt and the OIC Group, Inc.
 * @version 0.95
 *
 * @package Subsystems
 * @subpackage Configuration
 */

/**
 * @ignore
 */
@include_once(BASE."conf/config.php");
if (is_readable(BASE."conf/extensions")) {
	$dh = opendir(BASE."conf/extensions");
	while (($file = readdir($dh)) !== false) {
		if (is_readable(BASE."conf/extensions/$file") && substr($file,-13,13) == ".defaults.php") {
			/**
			 * @ignore
			 */
			include_once(BASE."conf/extensions/$file");
		}
	}
}

?>