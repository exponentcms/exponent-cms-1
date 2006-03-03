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

// Sanitize querystring parameters.
$_GET['id'] = intval($_GET['id']);

$post = $db->selectObject('weblog_post','id='.$_GET['id']);
if ($post) {
	$loc = unserialize($post->location_data);
	$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$post->id);
	
	if (exponent_permissions_check('delete',$loc) || exponent_permissions_check('delete',$iloc)) {
		$db->delete('weblog_post','id='.$_GET['id']);
		$db->delete('weblog_comment','parent_id='.$_GET['id']);
		exponent_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>