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

$news = null;
$iloc = null;

if (isset($_POST['id'])) {
	$news = $db->selectObject("newsitem","id=" . $_POST['id']);
	if ($news != null) {
		$loc = unserialize($news->location_data);
		$iloc = $loc;
		$iloc->int = $news->id;
	}
	$news->editor = $user->id;
	$news->edited = time();
} else {
	$news->posted = time();
	$news->poster = ($user?$user->id:0);
}

if (($news != null && pathos_permissions_check("edit_item",$loc)) || 
	($news == null && pathos_permissions_check("add_item",$loc)) ||
	($iloc != null   && pathos_permissions_check("edit_item",$iloc)) 
) {
	
	$news = newsitem::update($_POST,$news);
	$news->location_data = serialize($loc);
	
	if (!defined("SYS_WORKFLOW")) include_once(BASE."subsystems/workflow.php");
	pathos_workflow_post($news,"newsitem",$loc);
} else {
	echo SITE_403_HTML;
}

?>