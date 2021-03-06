<?php

##################################################
#
# Copyright (c) 2004-2011 OIC Group, Inc.
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

if (!defined("EXPONENT")) exit("");

// Sanitize required querystring parameter
$_GET['id'] = intval($_GET['id']);

$news = $db->selectObject("newsitem","id=" . $_GET['id']);
$iloc = null;
if ($news != null) {
	$loc = unserialize($news->location_data);
	$iloc = $loc;
	$iloc->int = $news->id;
	
	if (exponent_permissions_check("delete_item",$loc) || ($iloc != null && exponent_permissions_check("delete_item",$iloc))) {
		$db->delete("newsitem","id=" . $_GET['id']);
		$db->delete("newsitem_wf_info","real_id=".$_GET['id']);
		$db->delete("newsitem_wf_revision","wf_original=".$_GET['id']);
		//Delete search entries
		$db->delete('search',"ref_module='newsmodule' AND ref_type='newsitem' AND original_id=".$news->id);		
		exponent_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>
