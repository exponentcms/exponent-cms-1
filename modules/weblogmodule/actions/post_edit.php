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

if (!defined("PATHOS")) exit("");

$post = null;
$iloc = null;
if (isset($_GET['id'])) {
	$post = $db->selectObject("weblog_post","id=".$_GET['id']);
	$loc = unserialize($post->location_data);
	$iloc = pathos_core_makeLocation($loc->mod,$loc->src,$post->id);
}

if (($post == null && pathos_permissions_check("post",$loc)) ||
	($post != null && pathos_permissions_check("edit",$loc)) ||
	($post != null && pathos_permissions_check("edit",$iloc))
) {
	$form = weblog_post::form($post);
	$form->location($loc);
	$form->meta("action","post_save");
	
	$template = new template("weblogmodule","_form_postEdit",$loc);
	$template->assign("form_html",$form->toHTML());
	$template->assign("is_edit",isset($_GET['id']));
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>