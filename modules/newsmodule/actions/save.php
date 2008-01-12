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

//GREP:HARDCODEDTEXT2
if (!defined("EXPONENT")) exit("");

$news = null;
$iloc = null;

if (isset($_POST['id'])) {
	$news = $db->selectObject("newsitem","id=" . intval($_POST['id']));
	if ($news != null) {
		$loc = unserialize($news->location_data);
		$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$news->id);
	}
	$news->editor = $user->id;
	$news->edited = time();
} else {
	$news->posted = time();
	$news->poster = ($user?$user->id:0);
	$news->edited = time();
}

if ((isset($news->id) && exponent_permissions_check("edit_item",$loc)) || 
	(!isset($news->id) && exponent_permissions_check("add_item",$loc)) ||
	($iloc != null   && exponent_permissions_check("edit_item",$iloc)) 
) {

	//Get and save the image
        if ($_FILES['file']['name'] != '') {
                $dir = 'files/newsmodule/'.$loc->src;
                $file = file::update('file',$dir,null);
		//eDebug($file);
                if (is_object($file)) {
                        $news->file_id = $db->insertObject($file,'file');
                } else {
                        // If file::update() returns a non-object, it should be a string.  That string is the error message.
                        $post = $_POST;
                        $post['_formError'] = $file;
                        exponent_sessions_set('last_POST',$post);
                        header('Location: ' . $_SERVER['HTTP_REFERER']);
			exit();
                }
        }
	
	$news = newsitem::update($_POST,$news);
	//Get and add the tags selected by the user
	$news->tags = serialize(listbuildercontrol::parseData($_POST,'tags'));
		
	//not sure why this is here - added by James?
	/*if (!isset($news->id) && $db->countObjects('newsitem',"internal_name='".$news->internal_name."'")) {
		unset($_POST['internal_name']);
		$_POST['_formError'] = 'That Internal Name is already taken';
		exponent_sessions_set('last_POST',$_POST);
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		exit('');
	}
	*/
	
	$news->location_data = serialize($loc);

	if (!defined("SYS_WORKFLOW")) require_once(BASE."subsystems/workflow.php");
	exponent_workflow_post($news,"newsitem",$loc);
} else {
	echo SITE_403_HTML;
}

?>
