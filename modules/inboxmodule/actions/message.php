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

/**
 * View a Message
 *
 * @author James Hunt
 * @copyright 2004 James Hunt and the OIC Group, Inc.
 *
 * @package Modules
 * @subpackage Inbox
 */

if (!defined("PATHOS")) exit("");

if ($user) {
	$msg = $db->selectObject("privatemessage","id=".$_GET['id']);
	
	if ($msg) {
		if ($msg->recipient == $user->id) { // OR check for mng perms.
			$template = new template("inboxmodule","_viewmessage",$loc);
			
			$template->assign("message",$msg);
			
			$template->output();
			
			$msg->unread = 0;
			$db->updateObject($msg,"privatemessage");
		}
	} else echo SITE_404_HTML;
}

?>