<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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

// Part of the HTMLArea category

if (!defined('EXPONENT')) exit('');

if (exponent_permissions_check('htmlarea',exponent_core_makeLocation('administrationmodule'))) {
	$config = null;
	if (isset($_POST['id'])) $config = $db->selectObject('htmlareatoolbar','id='.intval($_POST['id']));
	$config->name = $_POST['config_name'];
	$config->data = array();
	foreach (explode(':',$_POST['config']) as $line) {
		$line = trim($line);
		if ($line != '') {
			$i = count($config->data);
			$config->data[] = array();
			foreach (explode(';',$line) as $icon) {
				$config->data[$i][] = $icon; // MAY need to strip off ed
			}
		}
	}
	$config->data = serialize($config->data);
	
	if (isset($_POST['config_activate'])) {
		$active = $db->selectObject('htmlareatoolbar','active=1');
		$active->active = 0;
		$db->updateObject($active,'htmlareatoolbar');
		$config->active = 1;
	}
	
	if (isset($config->id)) {
		$db->updateObject($config,'htmlareatoolbar');
	} else {
		$db->insertObject($config,'htmlareatoolbar');
	}
	
	exponent_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>