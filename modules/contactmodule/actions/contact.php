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

if (!defined("PATHOS")) exit("");

$template = new template("contactmodule",$_POST['msg'],$loc);
$template->assign("post",$_POST);

$msg = $template->render();

$config = $db->selectObject("contactmodule_config","location_data='".serialize($loc)."'");
if ($config == null) {
	$config->subject = "Email Communication From Site";
	$config->replyto_address = "";
	$config->from_address = "info@".$_SERVER["HTTP_HOST"];
	$config->from_name = "Exponent Website";
	
} else {
	if ($config->subject == "") {
		$config->subject = "Email Communication From Site";
	}
}

$headers = array();
$headers["From"] = $config->from_name . " <".$config->from_address.">";
if ($config->replyto_address != "") $headers["Reply-to"] = $config->replyto_address;

if (!defined("SYS_USERS")) include_once(BASE."subsystems/users.php");

$emails = array();
foreach ($db->selectObjects("contact_contact","location_data='".serialize($loc)."'") as $c) {
	if ($c->user_id != 0) {
		$u = pathos_users_getUserById($c->user_id);
		$emails[] = $u->email;
	} else if ($c->email != "") {
		$emails[] = $c->email;
	}
}

if (!defined("SYS_SMTP")) include_once(BASE."subsystems/smtp.php");
if (pathos_smtp_mail($emails,$config->from_address,$config->subject,$msg,$headers)) {
	pathos_flow_redirect();
} else {
	pathos_lang_load('modules','contactmodule');
	echo TR_CONTACTMODULE_SMTPERROR;
}

?>