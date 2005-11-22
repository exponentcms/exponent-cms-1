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

// Sanitize required querystring parameters.
$_GET['id'] = intval($_GET['id']);

$comment = $db->selectObject('weblog_comment','id='.$_GET['id']);
$post = $db->selectObject('weblog_post','id='.$comment->parent_id);

if ($comment != null && $post != null) {
	$loc = unserialize($post->location_data);
	$iloc = pathos_core_makeLocation($loc->mod,$loc->src,$post->id);
	
	if ((pathos_permissions_check('delete_comments',$loc)) ||
		(pathos_permissions_check('delete_comments',$iloc))
	) {
	
		$db->delete('weblog_comment','id='.$_GET['id']);
		pathos_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>