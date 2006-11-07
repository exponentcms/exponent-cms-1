<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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
# $Id: save_slide.php,v 1.6 2005/05/09 06:01:45 filetreefrog Exp $
##################################################

if (!defined('EXPONENT')) exit('');

$slide = null;
if (isset($_POST['id'])) {
	$slide = $db->selectObject('slideshow_slide','id='.$_POST['id']);
	if ($slide) {
		$loc = unserialize($slide->location_data);
	}
}

if (($slide == null && exponent_permissions_check('create_slide',$loc)) ||
	($slide != null && exponent_permissions_check('edit_slide',$loc))
) {
	$slide = slideshow_slide::update($_POST,$slide);
	$slide->location_data = serialize($loc);
	
	$directory = 'files/slideshowmodule/'.$loc->src;
	$file = file::update('file',$directory,null);
	if (is_object($file)) {
		if (isset($slide->id)) {
			// We have a slide already.  Delete the old one
			$oldfile = $db->selectObject('file','id='.$slide->file_id);
			file::delete($oldfile);
		}
		$slide->file_id = $db->insertObject($file,'file');
	} else {
		// If file::update() returns a non-object, it should be a string.  That string is the error message.
		$post = $_POST;
		$post['_formError'] = $file;
		exponent_sessions_set('last_POST',$post);
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		exit();
	}
	
	if (isset($slide->id)) {
		$db->updateObject($slide,'slideshow_slide');
	} else {
		$db->insertObject($slide,'slideshow_slide');
	}
	exponent_flow_redirect();
}

?>