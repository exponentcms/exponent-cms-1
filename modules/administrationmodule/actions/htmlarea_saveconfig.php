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

if (!defined("PATHOS")) exit("");

if ($user && $user->is_acting_admin) {

	$config = null;
	if (isset($_POST['id'])) $config = $db->selectObject("htmlareatoolbar","id=".$_POST['id']);
	$config->name = $_POST['config_name'];
	$config->data = array();
	foreach (explode(":",$_POST['config']) as $line) {
		$line = trim($line);
		if ($line != "") {
			$i = count($config->data);
			$config->data[] = array();
			foreach (explode(";",$line) as $icon) {
				$config->data[$i][] = $icon; // MAY need to strip off ed
			}
		}
	}
	$config->data = serialize($config->data);
	
	if (isset($_POST['config_activate'])) {
		$active = $db->selectObject("htmlareatoolbar","active=1");
		$active->active = 0;
		$db->updateObject($active,"htmlareatoolbar");
		$config->active = 1;
	}
	
	if (isset($config->id)) $db->updateObject($config,"htmlareatoolbar");
	else $db->insertObject($config,"htmlareatoolbar");
	
	pathos_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>