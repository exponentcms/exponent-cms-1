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

exponent_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_ACTION);

if ($user->is_admin || $user->is_acting_admin) {
	$where = '';
} else {
	$where = " AND (is_draft = 0 OR poster = ".($user ? $user->id : -1).")";
}
if (!exponent_permissions_check('view_private',$loc)) $where = ' AND is_private = 0';

$this_post = null;
if (isset($_GET['id'])) {
	$this_post = $db->selectObject('weblog_post','id='.intval($_GET['id']).$where);
} else if (isset($_GET['title'])) {
	$this_post = $db->selectObject('weblog_post',"title='".urldecode($_GET['title'])."'".$where);
} else if (isset($_GET['internal_name'])) {
	$this_post = $db->selectObject('weblog_post',"internal_name='".$_GET['internal_name']."'".$where);
}
$config = $db->selectObject('weblogmodule_config',"location_data='".$this_post->location_data."'");
if ($config == null) {
	$config->allow_comments = 1;
}

if ($this_post) {
	if ($this_post->is_draft == 0 || ($user && $this_post->poster == $user->id)) {
		if ($this_post->is_draft == 0) {
			#Added to count reads of each story
			$old_read_count = $this_post->reads;
			$new_read_count = $old_read_count + 1;
			$this_post->reads = $new_read_count;
			$db->updateObject($this_post,'weblog_post');
		}
	
		$this_post->posted = ($this_post->publish != 0 ? $this_post->publish : $this_post->posted);
		if ($this_post->publish == 0) {$this_post->publish = $this_post->posted;}
		$loc = unserialize($this_post->location_data);
	
//		$next_post = $db->selectObject('weblog_post',$where.' AND posted >= '.$this_post->posted.' AND id != '.$this_post->id);
//		$prev_post = $db->selectObject('weblog_post',$where.' AND posted <= '.$this_post->posted.' AND id != '.$this_post->id, 'posted DESC');

		$next_id = $db->min('weblog_post','id','location_data', $where.' AND id > '.$this_post->id);
		$next_post = $db->selectObject('weblog_post','id = '.$next_id);
		$prev_id = $db->max('weblog_post','id','location_data', $where.' AND id < '.$this_post->id);
		$prev_post = $db->selectObject('weblog_post','id = '.$prev_id);
		if (!$next_post) {
			$next_post = 0;
		}
		if (!$prev_post) {
			$prev_post = 0;
		}
		
		if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
	
		$ploc = exponent_core_makeLocation($loc->mod,$loc->src,$this_post->id);
		
		$this_post->permissions = array(
			'administrate'=>exponent_permissions_check('administrate',$ploc),
			'edit'=>exponent_permissions_check('edit',$ploc),
			'delete'=>exponent_permissions_check('delete',$ploc),
			'comment'=>exponent_permissions_check('comment',$ploc),
			'approve_comments'=>exponent_permissions_check('approve_comments',$ploc),
			'edit_comments'=>exponent_permissions_check('edit_comments',$ploc),
			'delete_comments'=>exponent_permissions_check('delete_comments',$ploc),
			'view_private'=>exponent_permissions_check('view_private',$ploc),
			'manage_approval'=>exponent_permissions_check('manage_approval',$ploc),
		);
		
		if (!exponent_permissions_check('approve_comments',$ploc) && $config->approve_comments) {
			$this_post->comments = $db->selectObjects('weblog_comment','parent_id='.$this_post->id." AND approved=1");
		} else {
			$this_post->comments = $db->selectObjects('weblog_comment','parent_id='.$this_post->id);
		}
		$this_post->total_comments = count($this_post->comments);
		usort($this_post->comments,'exponent_sorting_byPostedDescending');

		$title = $db->selectValue('container', 'title', "internal='".serialize($loc)."'");
		if (!defined("SYS_USERS")) require_once(BASE."subsystems/users.php");
		$u = exponent_users_getUserById($this_post->poster);
		if (!empty($u->id)) {
			$emailto = $u->email;   
		}
		
		$template = new template('weblogmodule','_view',$loc);
		
		$monitoring = false;
		if ($user && ($user->id!=0)) {
			$weblog_monitor = null;
			$weblog_monitor = $db->selectObject("weblog_monitor","weblog_id=".$config->id." AND user_id=".$user->id);
			if ($weblog_monitor != null) $monitoring = true;
		}
		$template->assign("monitoring", $monitoring);

		//Get the comment form and pass it to the template
		$form = weblog_comment::form(null);
		$form->location($loc);
		$form->meta('action','comment_save');
		$form->meta('parent_id',$this_post->id);
		$template->assign('form_html',$form->toHTML());  

		$template->assign('this_post',$this_post);
		$template->assign('next_post',$next_post);
		$template->assign('prev_post',$prev_post);
	
		$template->assign('logged_in', exponent_users_isLoggedIn());	
		if (exponent_users_isLoggedIn()) {
			$template->assign('user',$user);
		}
		$template->assign('subject',$config->reply_title.$this_post->title);
		$template->assign('emailto',$emailto);
		$template->assign('config',$config);
//		$template->assign('viewconfig',$template->viewconfig);
		$template->assign('moduletitle',$title);
	
		$template->output();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}
