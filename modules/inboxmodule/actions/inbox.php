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
 * Primary Inbox Interface
 *
 * @author James Hunt
 * @copyright 2004 James Hunt and the OIC Group, Inc.
 *
 * @package Modules
 * @subpackage Inbox
 */

if (!defined("PATHOS")) exit("");

if ($user) {
	pathos_flow_set(SYS_FLOW_PROTECTED, SYS_FLOW_ACTION);

	$messages = $db->selectObjects("privatemessage","recipient=".$user->id);
	function msgCmp($a,$b) {
		return ($a->date_sent > $b->date_sent ? -1 : 1);
	}
	usort($messages,"msgCmp");
	
	$read = $db->countObjects("privatemessage","recipient=".$user->id." AND unread=0");
	$unread = $db->countObjects("privatemessage","recipient=".$user->id." AND unread=1");
	
	$template = new template("inboxmodule","_viewmessages",$loc);
	
	$template->assign("user",$user);
	$template->assign("messages",$messages);
	$template->assign("readMessages",$read);
	$template->assign("unreadMessages",$unread);
	$template->assign("totalMessages",$unread+$read);
	
	$template->output();
}

?>