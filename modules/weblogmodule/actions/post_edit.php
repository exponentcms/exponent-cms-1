<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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
$iloc = null;
if (isset($_GET['id'])) {
	$post = $db->selectObject('weblog_post','id='.intval($_GET['id']));
	$loc = unserialize($post->location_data);
	$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$post->id);
}

if (($post == null && exponent_permissions_check('post',$loc)) ||
	($post != null && exponent_permissions_check('edit',$loc)) ||
	($post != null && exponent_permissions_check('edit',$iloc))
) {
	$form = weblog_post::form($post);
	$form->location($loc);
	$form->meta('action','post_save');
	
	$template = new template('weblogmodule','_form_postEdit',$loc);
	$template->assign('form_html',$form->toHTML());
	$template->assign('is_edit', (isset($_GET['id']) ? 1 : 0) );
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>