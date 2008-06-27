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

define('SCRIPT_EXP_RELATIVE','');
define('SCRIPT_FILENAME','edit_page.php');

ob_start();

include_once('exponent.php');
if (!defined("EXPONENT")) exit("");

if (!defined('SYS_THEME')) include_once(BASE.'subsystems/theme.php');

$id = -1;
if (isset($_GET['sitetemplate_id'])) {
	exponent_sessions_set('sitetemplate_id',intval($_GET['sitetemplate_id']));
	$id = intval($_GET['sitetemplate_id']);
} else if (exponent_sessions_isset('sitetemplate_id')) {
	$id = exponent_sessions_get('sitetemplate_id');
}

$template = $db->selectObject('section_template','id='.$id);
$page = ($template && $template->subtheme != '' && is_readable(BASE.'themes/'.DISPLAY_THEME.'/subthemes/'.$template->subtheme.'.php') ?
	'themes/'.DISPLAY_THEME.'/subthemes/'.$template->subtheme.'.php' :
	'themes/'.DISPLAY_THEME.'/index.php'
);

$i18n = exponent_lang_loadFile('modules/navigationmodule/actions/edit_page.php');

exponent_sessions_set('themeopt_override',array(
	'src_prefix'=>'@st'.$id,
	'ignore_mods'=>array(
		'navigationmodule',
		'loginmodule'
	),
	'mainpage'=>PATH_RELATIVE.'modules/navigationmodule/actions/edit_page.php',
	'backlinktext'=>$i18n['back']
));

#define('PREVIEW_READONLY',1);
$REDIRECTIONPATH = 'section_template';

if ($user && $user->is_acting_admin == 1) {
	if (is_readable(BASE.$page)) {
		include_once(BASE.$page);
	} else {
		echo sprintf($i18n['err_not_readable'],BASE.$page);
	}

	exponent_sessions_unset('themeopt_override');
} else {
	echo SITE_403_HTML;
}

?>
