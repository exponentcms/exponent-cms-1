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


if (!defined('BASE')) {
	/*
	 * BASE Constant
	 *
	 * The BASE constant is the absolute path on the server filesystem, from the root (/ or C:\)
	 * to the Exponent directory.
	 */
	define('BASE',__realpath(dirname(__FILE__)).'/');
}
/*
 * PATHOS Constant
 *
 * The PATHOS constant defines the current Major.Minor version of Exponent/Pathos (i.e. 0.95).
 * It's definition also signals to other parts of the system that they are operating within the confines
 * of the Pathos Framework.  (Module actions check this -- if it is not defined, they must abort).
 */
define('PATHOS',include(BASE.'pathos_version.php'));

if (!defined('PATH_RELATIVE')) {
	/*
	 * PATH_RELATIVE Constant
	 *
	 * The PATH_RELATIVE constant is the web path to the Exponent directory,
	 * from the web root.  It is related to the BASE constant, but different.
	 */
	define('PATH_RELATIVE',dirname($_SERVER['SCRIPT_NAME']) . '/');
}

if (!defined('URL_BASE')) {
	/*
	 * URL_BASE Constant
	 *
	 * The URL_BASE constant is the base URL of the domain hosting the Exponent site.
	 * It does not include the PATH_RELATIVE information.  The automatic
	 * detection code can figure out if the server is running in SSL mode or not
	 */
	define('URL_BASE',((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://') . $_SERVER['HTTP_HOST']);
}
if (!defined('URL_FULL')) {
	/*
	 * URL_FULL Constant
	 *
	 * The URL_FULL constant is the full URL path to the Exponent directory.  The automatic
	 * detection code can figure out if the server is running in SSL mode or not.
	 */
	define('URL_FULL',URL_BASE.PATH_RELATIVE);
}

?>