<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Written and Designed by James Hunt
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################

if (!defined('EXPONENT')) exit('');

// Initialize the Theme Subsystem
if (!defined('SYS_THEME')) require_once(BASE.'subsystems/theme.php');

$section = (exponent_sessions_isset('last_section') ? exponent_sessions_get('last_section') : SITE_DEFAULT_SECTION);
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
	if (exponent_sessions_isset('source_select')) $source_select = exponent_sessions_get('source_select');
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
		else if ($_REQUEST['showmodules'] == 'all') $source_select['showmodules'] = null;
		else $source_select['showmodules'] = explode(',',$_REQUEST['showmodules']);
	} else if (!isset($source_select['showmodules'])) {
		$source_select['showmodules'] = null;
	}
	
	if (isset($_REQUEST['dest'])) {
		$source_select['dest'] = $_REQUEST['dest'];
	} else if (!isset($source_select['dest'])) {
		$source_select['dest'] = null;
	}
	
	if (isset($_REQUEST['hideOthers'])) {
		$source_select['hideOthers'] = $_REQUEST['hideOthers'];
	} else if (!isset($source_select['hideOthers'])) {
		$source_select['hideOthers'] = 0;
	}
	
	exponent_sessions_set('source_select',$source_select);
	// Include the rendering page.
	include_once(BASE.$page);
} else {
	$i18n = exponent_lang_loadFile('selector.php');
	echo sprintf($i18n['not_readable'],BASE.$page);
}

?>