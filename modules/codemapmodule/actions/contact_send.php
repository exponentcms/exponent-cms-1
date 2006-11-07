<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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
# $Id: contact_send.php,v 1.4 2005/04/26 02:52:46 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

$stepstone = null;
if (isset($_POST['stepstone_id'])) $stepstone = $db->selectObject("codemap_stepstone","id=".$_POST['stepstone_id']);
if ($stepstone) {
	$milestone = $db->selectObject("codemap_milestone","id=".$stepstone->milestone_id);
	
	$from = $_POST['email'];
	$headers = array(
		"From"=>$_POST['name'] . "<$from>",
		"Reply-to"=>$_POST['email']
	);
	$message = "";
	$subject = "";
	
	if ($_POST['type'] == "dev") {
		$message = $_POST['message'];
		$subject = "Software Roadmap : " . $_POST['subject'];
	} else if ($_POST['type'] == "vol") {
		$message = "Someone wishes to volunteer for the following stepping stone:\n\n";
		$message .= $stepstone->name . "\n";
		$message .= "Target Milestone: " . $milestone->name . "\n\n";
		$message .= $stepstone->description . "\n";
		$message .= "----------------------------------\n";
		$message .= "Name:  " . $_POST['name'] . "\n";
		$message .= "Email: " . $_POST['email'] . "\n";
		$message .= "----------------------------------\n";
		$message .= "Reasons for Volunteering:\n";
		$message .= wordwrap($_POST['reasons']) . "\n";
		$message .= "----------------------------------\n";
		$message .= "How they feel they can help:\n";
		$message .= wordwrap($_POST['howhelp']) . "\n";
		$message .= "----------------------------------\n";
		$message .= "Past experience and qualifications:\n";
		$message .= wordwrap($_POST['experience']) . "\n";
		
		$subject = "Software Roadmap : Volunteer for " . $milestone->name . ":" . $stepstone->name;
	} else {
		echo SITE_404_HTML;
		return; // exit this page
	}
	
	if (!defined("SYS_SMTP")) require_once(BASE."subsystems/smtp.php");
	if (exponent_smtp_mail($stepstone->contact,$from,$subject,$message,$headers)) {
		echo "Message successfully sent.";
	} else {
		echo "There was a problem sending the message.  Please contact the system administrator.";
	}
	
	echo "<br /><br /><a href='".exponent_flow_get()."' class='mngmntlink codemap_mngmntlink'>Back</a>";
} else {
	echo SITE_404_HTML;
}

?>