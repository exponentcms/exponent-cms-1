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

if (!defined("BASE")) define("BASE",__realpath(dirname(__FILE__))."/");
define("PATHOS",include(BASE."pathos_version.php"));

if (!defined("PATH_RELATIVE")) {
	if (isset($_SERVER['DOCUMENT_ROOT'])) {
			define("PATH_RELATIVE",str_replace(__realpath($_SERVER['DOCUMENT_ROOT']),"",BASE));
	} else {
			// For anybody without DOCUMENT_ROOT
			define("PATH_RELATIVE",dirname($_SERVER['SCRIPT_NAME']) . "/");
	}
}

if (!defined("URL_BASE")) define("URL_BASE",((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https://" : "http://") . $_SERVER['HTTP_HOST']);
if (!defined("URL_FULL")) define("URL_FULL",URL_BASE.PATH_RELATIVE);

?>