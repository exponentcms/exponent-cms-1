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

define('SCRIPT_EXP_RELATIVE','');
define('SCRIPT_FILENAME','index.php');

ob_start();
$microtime_str = explode(' ',microtime());
$i_start = $microtime_str[0] + $microtime_str[1];

// Initialize the Pathos Framework
include_once('pathos.php');

pathos_lang_loadDictionary('standard','base');

// Initialize the theme subsystem
if (!defined('SYS_THEME')) include_once(BASE.'subsystems/theme.php');

if (!DEVELOPMENT && @file_exists(BASE.'install/not_configured')) {
	header('Location: install/index.php?page=welcome');
	exit();
}

// Handle sub themes
$page = ($section && $section->subtheme != '' && is_readable(BASE.'themes/'.DISPLAY_THEME.'/subthemes/'.$section->subtheme.'.php') ?
	BASE.'themes/'.DISPLAY_THEME.'/subthemes/'.$section->subtheme.'.php' :
	BASE.'themes/'.DISPLAY_THEME.'/index.php'
);

if (is_readable($page)) {
	include_once($page);
} else echo sprintf(TR_BASE_PAGENOTREADABLE,$page);

$microtime_str = explode(' ',microtime());
$i_end = $microtime_str[0] + $microtime_str[1];

echo sprintf(TR_BASE_EXECUTIONTIME,round($i_end - $i_start,4));
ob_end_flush();

?>