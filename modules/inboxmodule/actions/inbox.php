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

if ($user) {
	exponent_flow_set(SYS_FLOW_PROTECTED, SYS_FLOW_ACTION);

	$messages = $db->selectObjects('privatemessage','recipient='.$user->id);
	function msgCmp($a,$b) {
		return ($a->date_sent > $b->date_sent ? -1 : 1);
	}
	usort($messages,'msgCmp');
	
	$read = $db->countObjects('privatemessage','recipient='.$user->id.' AND unread=0');
	$unread = $db->countObjects('privatemessage','recipient='.$user->id.' AND unread=1');
	
	$template = new template('inboxmodule','_viewmessages',$loc);
	
	$template->assign('user',$user);
	$template->assign('messages',$messages);
	$template->assign('readMessages',$read);
	$template->assign('unreadMessages',$unread);
	$template->assign('totalMessages',$unread+$read);
	
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>