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

if (!defined('PATHOS')) exit('');

// PERM CHECK
	pathos_lang_loadDictionary('modules','sharedcoremodule');

	$core = null;
	if (isset($_POST['id'])) $core = $db->selectObject('sharedcore_core','id='.$_POST['id']);
	
	$core = sharedcore_core::update($_POST,$core);
	
	$existing = $db->countObjects('sharedcore_core',"path='".$core->path."'");
	if ($existing) {
		$post = $_POST;
		$post['_formError'] = sprintf(TR_SHAREDCOREMODULE_COREEXISTS,$core->path);
		pathos_sessions_set('last_POST',$post);
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		exit();
	}
	
	if (substr($core->path,-1,1) != '/') $core->path .= '/';
	if (file_exists($core->path.'pathos_version.php')) {
		if (isset($core->id)) $db->updateObject($core,'sharedcore_core');
		else $db->insertObject($core,'sharedcore_core');
		
		pathos_flow_redirect();
	} else {
		$post = $_POST;
		$post['_formError'] = sprintf(TR_SHAREDCOREMODULE_ERR_INVALIDCORE,$core->path);
		pathos_sessions_set('last_POST',$post);
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
// END PERM CHECK

?>