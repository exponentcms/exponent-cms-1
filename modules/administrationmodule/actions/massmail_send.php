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
//GREP:HARDCODEDTEXT
if (!defined("PATHOS")) exit("");

if ($user && $user->is_admin) {
	if (!defined("SYS_USERS")) include_once(BASE."subsystems/users.php");
	
	$to_r = array();
	foreach (pathos_users_getAllUsers() as $u) {
		if ($u->email != "") $to_r[] = $u->email;
	}
	
	if (!defined("SYS_SMTP")) include_once(BASE."subsystems/smtp.php");
	$headers = array(
		"MIME-Version"=>"1.0",
		"Content-type"=>"text/html; charset=iso-8859-1",
		"Reply-to"=>$_POST['replyto'],
		"From"=>$_POST['from']
	);
	
	$msg = "<html>".$_POST['message']."</html>";
	
	if ($_POST['to_type'] == 1) { // Preview
		if (!pathos_smtp_mail($_POST['to'][1],$_POST['returnpath'],$_POST['subject'],$msg,$headers)) {
			$post = $_POST;
			$post['_formError'] =  "Error while sending preview email.";
			pathos_sessions_set("last_POST",$post);
		}
		pathos_sessions_set("massmail_data",$_POST);
		header("Location: " . $_SERVER['HTTP_REFERER']);
	} else {
		if (!pathos_smtp_mail($to_r,$_POST['returnpath'],$_POST['subject'],$msg,$headers)) {
			echo "Error while sending email.  This may be caused by incorrect SMTP settings in the Exponent Site Configuration.";
		} else {
			$template = new template("administrationmodule","_massmail_sent",$loc);
			$template->assign("addresses",$to_r);
			$template->output();
		}
	}
} else {
	echo SITE_403_HTML;
}

?>