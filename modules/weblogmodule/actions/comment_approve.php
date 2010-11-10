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

$comment = $db->selectObject('weblog_comment','id='.intval($_GET['id']));
if ($comment ) {
	if ($user) {
		if ($comment->approved == 0) {
			$comment->approved = 1;
		} else {
			$comment->approved = 0;
		}
		$db->updateObject($comment,'weblog_comment');
//		exponent_sessions_clearAllUsersSessionCache('weblogmodule');
		exponent_flow_redirect();
	}
} else {
    header("HTTP/1.1 404 Not Found");
    echo SITE_404_HTML;
}

?>