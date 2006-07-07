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

if (!defined('SYS_SMTP')) require_once(BASE.'subsystems/smtp.php');

//filter the message thru the form template for formatting
$msgtemplate = new formtemplate('email', '_'.$_POST['formname']);
$msgtemplate->assign('post', $_POST);
$msg = $msgtemplate->render();
$ret = false;

//make sure we this is from a valid event and that the email addresses are listed, then mail
if (isset($_POST['id'])) {
	$event = $db->selectObject('calendar','id='.intval($_POST['id']));
	$email_addrs = array();
	if ($event->feedback_email != '') {
		$email_addrs = split(',', $event->feedback_email);
		$email_addrs = array_map('trim', $email_addrs);
		$ret = exponent_smtp_mail($email_addrs, SMTP_FROMADDRESS,$_POST['subject'],$msg);
	}
}

$template = new template('calendarmodule','_feedback_submitted');
$template->assign('success',($ret?1:0));
$template->output();

?>
