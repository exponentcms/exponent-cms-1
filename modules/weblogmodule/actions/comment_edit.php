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

$post = null;
$comment = null;
if (isset($_GET['id'])) {
	$comment = $db->selectObject('weblog_comment','id='.intval($_GET['id']));
	$post = $db->selectObject('weblog_post','id='.$comment->parent_id);
} else if (isset($_GET['parent_id'])) {
	$post = $db->selectObject('weblog_post','id='.intval($_GET['parent_id']));
}
if ($post && $post->is_draft == 0) {
	$loc = unserialize($post->location_data);
	$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$post->id);

	if ((!$comment && exponent_permissions_check('comment',$loc)) ||
		(!$comment && exponent_permissions_check('comment',$iloc)) ||
		($comment && exponent_permissions_check('edit_comments',$loc)) ||
		($comment && exponent_permissions_check('edit_comments',$iloc))
	) {
		$form = weblog_comment::form($comment);
		$form->location($loc);
		$form->meta('action','comment_save');
		
		if (isset($_GET['parent_id'])) {
			$form->meta('parent_id',intval($_GET['parent_id']));
		}
		
		$template = new template('weblogmodule','_form_commentEdit',$loc);
		$template->assign('form_html',$form->toHTML());
		$template->assign('is_edit', (isset($_GET['id']) ? 1 : 0) );
		$template->output();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>