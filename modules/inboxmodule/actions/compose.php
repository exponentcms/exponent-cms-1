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

if ($user) {
	$msg = null;
	if (isset($_GET['recipient'])) {
		$msg->recipient = $_GET['recipient'];
	}
	if (isset($_GET['replyto'])) {
		$orig = $db->selectObject("privatemessage","id=".$_GET['replyto']);
		$msg->recipient = $orig->from_id;
		$msg->subject = "Re: " . $orig->subject;
		$msg->body = "";
	} else {
		if (isset($_GET['subject'])) $msg->subject = $_GET['subject'];
		if (isset($_GET['body'])) $msg->body = $_GET['body'];
	}
	$form = privatemessage::form($msg);
	$form->meta("module","inboxmodule");
	$form->meta("action","send");

	$template = new template("inboxmodule","_form_compose",$loc);
	$template->assign("form_html",$form->toHTML());
	$template->output();
}

?>