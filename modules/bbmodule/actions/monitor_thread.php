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
# $Id: monitor_thread.php,v 1.3 2005/02/19 16:42:19 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

if ($user) {
	$thread = $db->selectObject("bb_post","id=".$_GET['id']);
	if ($thread) {
    $existing = $db->selectObject("bb_threadmonitor","thread_id=".$thread->id." OR thread_id=".$thread->parent." AND user_id=".$user->id);
		if ($existing && $_GET['monitor'] == 1 || !$existing && $_GET['monitor'] == 0) {
			// No change
			exponent_flow_redirect();
		} else {
			if ($_GET['monitor'] == 0) {
				$db->delete("bb_threadmonitor","thread_id=".$thread->id." AND user_id=".$user->id);
				
				exponent_flow_redirect();
			} else {
				if ($user->email != "") {
					$mon = null;
					$mon->thread_id = $thread->id;
					$mon->user_id = $user->id;
					$db->insertObject($mon,"bb_threadmonitor");
					
					exponent_flow_redirect();
				} else {
					echo "You do not have a valid email address in your user profile.";
				}
			}
		}
	} else echo SITE_404_HTML;
}

?>
