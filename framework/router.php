<?php
##################################################
#
# Copyright (c) 2007-2008 OIC Group, Inc.
# Written and Designed by Adam Kessler
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

//eDebug($_SERVER);
$url_parts = array();
if (isset($_SERVER['REDIRECT_URL'])) {
	$url = substr_replace($_SERVER['REDIRECT_URL'],'',0,strlen(PATH_RELATIVE));
	$url_parts = explode('/', $url);
	//eDebug("URL: ".$url);
	//eDebug($url_parts);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// DO NOTHING FOR A POST REQUEST?
} elseif (count($url_parts) < 1 || $url_parts[0] == '' || $url_parts[0] == null) {
	$_REQUEST['section'] = SITE_DEFAULT_SECTION;
} elseif (count($url_parts) == 1) {
	global $db;

	// Try to look up the page by sef_name first.  If that doesn't exist, strip out the dashes and 
	// check the regular page names.  If that still doesn't work then we'll redirect them to the 
	// search module using the page name as the seach string.
        $section = $db->selectObject('section', 'sef_name="'.$url_parts[0].'"');
        if (empty($section)) {
                $name = str_ireplace('-', ' ', $url_parts[0]);
                $name2 = str_ireplace('-', '&nbsp;', $url_parts[0]);
                $section = $db->selectObject('section', 'name="'.$name.'" OR name="'.$name2.'"');
        }

	// if section is still empty then we should route the user to the search (cool new feature :-) )
	// at some point we need to write a special action/view for the search module that lets the user
	// know they were redirected to search since the page they tried to go to directly didn't exist.
	if (empty($section)) {
		redirect_to(array('controller'=>'searchmodule', 'action'=>'search', 'search_string'=>$url_parts[0]));
	}

	$_REQUEST['section'] = $section->id;
} else {
	// If we have three url parts we assume they are controller->action->id, otherwise split them out into name<=>value pairs
	if (count($url_parts) == 3) {
		$_REQUEST['id'] = $url_parts[2];
	        $_GET['id'] = $url_parts[2];
	} else {
		for($i=2; $i < count($url_parts); $i++ ) {
			if ($i % 2 == 0) {
				$_REQUEST[$url_parts[$i]] = isset($url_parts[$i+1]) ? $url_parts[$i+1] : '';
				$_GET[$url_parts[$i]] = isset($url_parts[$i+1]) ? $url_parts[$i+1] : '';
			}
		}
	}	

	//Figure out if this is module or controller request
	if (is_readable(BASE.'controllers/'.$url_parts[0].'Controller.php')) {
		$requestType = 'controller';
	} elseif (is_dir(BASE.'modules/'.$url_parts[0])) {
		$requestType = 'module';
	} 

	// Set the module or controller
	$_REQUEST[$requestType] = $url_parts[0];
	$_GET[$requestType] = $url_parts[0];
	$_POST[$requestType] = $url_parts[0];
	
	// Set the action for this module or controller
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$action = $_POST['action'];
	} else {
		$action = $url_parts[1];
	}
	
	$_REQUEST['action'] = $action;
	$_GET['action'] = $action;
	$_POST['action'] = $action;
}

?>

