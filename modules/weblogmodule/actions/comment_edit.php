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

$post = null;
$comment = null;
if (isset($_GET['parent_id'])) {
	$post = $db->selectObject('weblog_post','id='.$_GET['parent_id']);
} else if (isset($_GET['id'])) {
	$comment = $db->selectObject('weblog_comment','id='.$_GET['id']);
	$post = $db->selectObject('weblog_post','id='.$comment->parent_id);
}
if ($post) {
	$loc = unserialize($post->location_data);
	$iloc = pathos_core_makeLocation($loc->mod,$loc->src,$post->id);

	if ((!$comment && pathos_permissions_check('comment',$loc)) ||
		(!$comment && pathos_permissions_check('comment',$iloc)) ||
		($comment && pathos_permissions_check('edit_comments',$loc)) ||
		($comment && pathos_permissions_check('edit_comments',$iloc))
	) {
		$form = weblog_comment::form($comment);
		$form->location($loc);
		$form->meta('action','comment_save');
		
		if (isset($_GET['parent_id'])) $form->meta('parent_id',$_GET['parent_id']);
		
		$template = new template('weblogmodule','_form_commentEdit',$loc);
		$template->assign('form_html',$form->toHTML());
		$template->assign('is_edit',isset($_GET['id']));
		$template->output();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>