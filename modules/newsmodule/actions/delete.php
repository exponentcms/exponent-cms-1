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

if (!defined("PATHOS")) exit("");

$news = $db->selectObject("newsitem","id=" . $_GET['id']);
$iloc = null;
if ($news != null) {
	$loc = unserialize($news->location_data);
	$iloc = $loc;
	$iloc->int = $news->id;
	
	if (pathos_permissions_check("delete_item",$loc) || ($iloc != null && pathos_permissions_check("delete_item",$iloc))) {
		$db->delete("newsitem","id=" . $_GET['id']);
		$db->delete("newsitem_wf_info","real_id=".$_GET['id']);
		$db->delete("newsitem_wf_revision","wf_original=".$_GET['id']);
		pathos_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>