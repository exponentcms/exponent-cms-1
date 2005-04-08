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

# Following code taken from http://us4.php.net/manual/en/function.get-magic-quotes-gpc.php
#   - it allows magic_quotes to be on without screwing stuff up. 
if (get_magic_quotes_gpc()) {
	function stripslashes_deep($value) {
		return is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
	}

	$_POST = stripslashes_deep($_POST);
	$_GET = stripslashes_deep($_GET);
	$_COOKIE = stripslashes_deep($_COOKIE);
}

// pathos.php (the file that includes this file the most) will define this for its own purposes
// but for other scripts that want to bootstrap minimally, we will need it, so only define it
// if it isn't already defined.
if (!function_exists('__realpath')) {
	function __realpath($path) {
		$path = str_replace('\\','/',realpath($path));
		if ($path{1} == ':') {
			// We can't just check for C:/, because windows users may have the IIS webroot on X: or F:, etc.
			$path = substr($path,2);
		}
		return $path;
	}
}

// Process user-defined constants in overrides.php
include_once(dirname(__realpath(__FILE__)).'/overrides.php');

// Auto-detect whatever variables the user hasn't overridden in overrides.php
include_once(dirname(__realpath(__FILE__)).'/pathos_variables.php');

// Process PHP-wrapper settings (ini_sets and setting detectors)
include_once(dirname(__realpath(__FILE__)).'/pathos_setup.php');

// Initialize the Compatibility Layer
include(BASE.'compat.php');

?>