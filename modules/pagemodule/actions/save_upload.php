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
# $Id: save_upload.php,v 1.6 2005/05/09 06:01:29 filetreefrog Exp $
##################################################

if (!defined('EXPONENT')) exit('');

if (exponent_permissions_check('configure',$loc)) {
	$config = $db->selectObject('pagemodule_config',"location_data='".serialize($loc)."'");
	$file = null;
	if ($config && $config->file_id != 0) {
		// Delete the original file
		$file = $db->selectObject('file','id='.$config->file_id);
		file::delete($file);
		$config->file_id = 0;
	}
	// Clear the file path, if it exists.
	$config->filepath = '';
	$config->location_data = serialize($loc);
	
	$file = file::update('file','files/pagemodule/'.$loc->src,null);
	if (is_object($file)) {
		$config->file_id = $db->insertObject($file,'file');
		
		if (!isset($config->id)) {
			$db->insertObject($config,'pagemodule_config');
		} else {
			$db->updateObject($config,'pagemodule_config');
		}
		
		exponent_flow_redirect();
	} else {
		// If file::update() returns a non-object, it should be a string.  That string is the error message.
		$post = $_POST;
		$post['_formError'] = $file;
		exponent_sessions_set('last_POST',$post);
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
}

?>