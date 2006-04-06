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

if (exponent_permissions_check('manage_core',exponent_core_makeLocation('sharedcoremodule'))) {
	$i18n = exponent_lang_loadFile('modules/sharedcoremodule/actions/save_core.php');

	$core = null;
	if (isset($_POST['id'])) {
		$core = $db->selectObject('sharedcore_core','id='.intval($_POST['id']));
	}
	
	$core = sharedcore_core::update($_POST,$core);
	
	$existing = $db->countObjects('sharedcore_core',"path='".$core->path."'");
	if ($existing && !isset($core->id)) {
		$post = $_POST;
		$post['_formError'] = sprintf($i18n['core_exists'],$core->path);
		exponent_sessions_set('last_POST',$post);
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		exit('Redirecting...');
	}
	
	if (substr($core->path,-1,1) != '/') {
		$core->path .= '/';
	}
	
	if (file_exists($core->path.'exponent_version.php')) {
		if (isset($core->id)) $db->updateObject($core,'sharedcore_core');
		else $db->insertObject($core,'sharedcore_core');
		
		exponent_flow_redirect();
	} else {
		$post = $_POST;
		$post['_formError'] = sprintf($i18n['bad_core'],$core->path);
		exponent_sessions_set('last_POST',$post);
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
} else {
	echo SITE_403_HTML;
}

?>