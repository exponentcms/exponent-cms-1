<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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

pathos_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_ACTION);

$config = $db->selectObject('weblogmodule_config',"location_data='".serialize($loc)."'");
if ($config == null) {
	$config->allow_comments = 1;
}

$where = ' AND is_private = 0';
if (pathos_permissions_check('view_private',$loc)) $where = '';

$this_post = null;
if (isset($_GET['id'])) {
	$this_post = $db->selectObject('weblog_post','id='.$_GET['id'].$where);
} else if (isset($_GET['internal_name'])) {
	$this_post = $db->selectObject('weblog_post',"internal_name='".$_GET['internal_name']."'".$where);
}

if ($this_post) {
	$loc = unserialize($this_post->location_data);

	$next_post = $db->selectObject('weblog_post',"location_data='".$this_post->location_data."' AND posted >= ".$this_post->posted." AND id != ".$this_post->id.$where);
	$prev_post = $db->selectObject('weblog_post',"location_data='".$this_post->location_data."' AND posted <= ".$this_post->posted." AND id != ".$this_post->id.$where);
	if (!$next_post) {
		$next_post = 0;
	}
	if (!$prev_post) {
		$prev_post = 0;
	}
	
	if (!defined('SYS_SORTING')) include_once(BASE.'subsystems/sorting.php');

	$ploc = pathos_core_makeLocation($loc->mod,$loc->src,$this_post->id);
	
	$this_post->permissions = array(
		'administrate'=>pathos_permissions_check('administrate',$ploc),
		'edit'=>pathos_permissions_check('edit',$ploc),
		'delete'=>pathos_permissions_check('delete',$ploc),
		'comment'=>pathos_permissions_check('comment',$ploc),
		'edit_comments'=>pathos_permissions_check('edit_comments',$ploc),
		'delete_comments'=>pathos_permissions_check('delete_comments',$ploc),
		'view_private'=>pathos_permissions_check('view_private',$ploc),
	);
	
	$this_post->comments = $db->selectObjects('weblog_comment','parent_id='.$this_post->id);
	usort($this_post->comments,'pathos_sorting_byPostedDescending');

	$template = new template('weblogmodule','_view',$loc);

	$template->assign('this_post',$this_post);
	$template->assign('next_post',$next_post);
	$template->assign('prev_post',$prev_post);
	
	$template->assign('config',$config);
	
	$template->output();
} else {
	echo SITE_404_HTML;
}