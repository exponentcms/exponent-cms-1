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

$config = $db->selectObject('weblogmodule_config',"location_data='".serialize($loc)."'");
if ($config == null) {
	$config->allow_comments = 1;
}

$where = '';
if (!exponent_permissions_check('view_private',$loc)) $where = ' AND is_private = 0';

$this_post = null;
if (isset($_GET['id'])) {
	$this_post = $db->selectObject('weblog_post','id='.intval($_GET['id']).$where);
} else if (isset($_GET['internal_name'])) {
	$this_post = $db->selectObject('weblog_post',"internal_name='".$_GET['internal_name']."'".$where);
}

$where = "location_data='".$this_post->location_data."' AND (is_draft = 0 OR poster = ".($user ? $user->id : -1).")";
if (!exponent_permissions_check('view_private',$loc)) $where .= ' AND is_private = 0';

if ($this_post) {
	if ($this_post->is_draft == 0 || ($user && $this_post->poster == $user->id)) {
		$loc = unserialize($this_post->location_data);
	
		$next_post = $db->selectObject('weblog_post',$where.' AND posted >= '.$this_post->posted.' AND id != '.$this_post->id);
		$prev_post = $db->selectObject('weblog_post',$where.' AND posted <= '.$this_post->posted.' AND id != '.$this_post->id);
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
			'edit_comments'=>exponent_permissions_check('edit_comments',$ploc),
			'delete_comments'=>exponent_permissions_check('delete_comments',$ploc),
			'view_private'=>exponent_permissions_check('view_private',$ploc),
		);
		
		$this_post->comments = $db->selectObjects('weblog_comment','parent_id='.$this_post->id);
    $this_post->total_comments = count($this_post->comments);
		usort($this_post->comments,'exponent_sorting_byPostedDescending');

		$template = new template('weblogmodule','_view',$loc);
   
    //Get the comment form and pass it to the template
    $form = weblog_comment::form(null);
    $form->location($loc);
    $form->meta('action','comment_save');
    $form->meta('parent_id',$this_post->id);
    
    $template->assign('form_html',$form->toHTML());  
		$template->assign('this_post',$this_post);
		$template->assign('next_post',$next_post);
		$template->assign('prev_post',$prev_post);
		
		$template->assign('config',$config);
		
		$template->output();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}
