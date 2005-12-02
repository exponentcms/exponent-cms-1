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

if (!defined('PATHOS')) exit('');

if ($user) {
	$msg = null;
	if (isset($_GET['recipient'])) {
		$msg->recipient = $_GET['recipient'];
	}
	if (isset($_GET['replyto'])) {
		$orig = $db->selectObject('privatemessage','id='.intval($_GET['replyto']));
		$msg->recipient = $orig->from_id;
		$msg->subject = 'Re: ' . $orig->subject;
		$msg->body = '';
	} else {
		if (isset($_GET['subject'])) {
			$msg->subject = $_GET['subject'];
		}
		if (isset($_GET['body'])) {
			$msg->body = $_GET['body'];
		}
	}
	$form = privatemessage::form($msg);
	$form->meta('module','inboxmodule');
	$form->meta('action','send');

	$template = new template('inboxmodule','_form_compose',$loc);
	$template->assign('form_html',$form->toHTML());
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>