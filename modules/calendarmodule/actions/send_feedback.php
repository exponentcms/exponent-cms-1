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
if (!defined('SYS_SMTP')) include_once(BASE.'subsystems/smtp.php');

//filter the message thru the form template for formatting
$msgtemplate = new formtemplate('email', '_'.$_POST['formname']);
$msgtemplate->assign('post', $_POST);
$msg = $msgtemplate->render();
$ret = false;

//make sure we this is from a valid event and that the email addresses are listed, then mail
if (isset($_POST['id'])) {
	$event = $db->selectObject('calendar','id='.$_POST['id']);
	$email_addrs = array();
	if ($event->feedback_email != '') {
		$email_addrs = split(',', $event->feedback_email);
		$email_addrs = array_map('trim', $email_addrs);
		$ret = pathos_smtp_mail($email_addrs, 'website@'.HOSTNAME,$_POST['subject'],$msg);
	}
}

$template = new template('calendarmodule','_feedback_submitted');
$template->assign('success',($ret?1:0));
$template->output();

?>