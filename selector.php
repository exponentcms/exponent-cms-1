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
//GREP:HARDCODEDTEXT

ob_start();
// Initialize the Theme Subsystem
if (!defined('SYS_THEME')) include_once(BASE.'subsystems/theme.php');

$section = (pathos_sessions_isset('last_section') ? pathos_sessions_get('last_section') : SITE_DEFAULT_SECTION);
$section = $db->selectObject('section','id='.$section);

// Handle sub themes
$page = ($section && $section->subtheme != '' && is_readable('themes/'.DISPLAY_THEME.'/subthemes/'.$section->subtheme.'.php') ?
	'themes/'.DISPLAY_THEME.'/subthemes/'.$section->subtheme.'.php' :
	'themes/'.DISPLAY_THEME.'/index.php'
);

if (is_readable(BASE.$page)) {
	define('PREVIEW_READONLY',1); // for mods
	define('SELECTOR',1);
	$SYS_FLOW_REDIRECTIONPATH='source_selector';

	$source_select = array();
	if (pathos_sessions_isset('source_select')) $source_select = pathos_sessions_get('source_select');
	$count_orig = count($source_select);
	
	if (isset($_REQUEST['vview'])) {
		$source_select['view'] = $_REQUEST['vview'];
	} else if (!isset($source_select['view'])) {
		$source_select['view'] = '_sourcePicker';
	}
	
	if (isset($_REQUEST['vmod'])) {
		$source_select['module'] = $_REQUEST['vmod'];
	} else if (!isset($source_select['module'])) {
		$source_select['module'] = 'containermodule';
	}
	
	if (isset($_REQUEST['showmodules'])) {
		if (is_array($_REQUEST['showmodules'])) $source_select['showmodules'] = $_REQUEST['showmodules'];
		else if ($_REQUEST['showmodules'] == "all") $source_select['showmodules'] = null;
		else $source_select['showmodules'] = explode(",",$_REQUEST['showmodules']);
	} else if (!isset($source_select['showmodules'])) {
		$source_select['showmodules'] = null;
	}
	
	if (isset($_REQUEST['dest'])) {
		$source_select["dest"] = $_REQUEST['dest'];
	} else if (!isset($source_select['dest'])) {
		$source_select['dest'] = null;
	}
	
	if (isset($_REQUEST['hideOthers'])) {
		$source_select["hideOthers"] = $_REQUEST['hideOthers'];
	} else if (!isset($source_select['hideOthers'])) {
		$source_select['hideOthers'] = 0;
	}
	
	pathos_sessions_set("source_select",$source_select);
	
	// Include the rendering page.
	include_once(BASE.$page);
} else echo BASE.$page." not readable";

ob_end_flush();

?>