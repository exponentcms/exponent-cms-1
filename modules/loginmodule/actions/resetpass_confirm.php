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

// PERM CHECK
	$db->delete("passreset_token","expires < " . time());
	$tok = $db->selectObject("passreset_token","uid=".trim($_GET['uid'])." AND token='".trim($_GET['token']) ."'");
	if ($tok == null) {
		echo "Your token has expired.";
	} else {
		$newpass = "";
		for ($i = 0; $i < rand(12,20); $i++) {
			$num=rand(48,122);
			if(($num > 97 && $num < 122) || ($num > 65 && $num < 90) || ($num >48 && $num < 57)) $newpass.=chr($num);
			else $i--;
		}

		// Send message
		if (!defined("SYS_SMTP")) include_once(BASE."subsystems/smtp.php");
		
		$template = new template("loginmodule","_email_resetdone",$loc);
		$template->assign("newpass",$newpass);
		$msg = $template->render();
		
		if (!defined("SYS_USERS")) include_once(BASE."subsystems/users.php");
		$u = pathos_users_getUserById($tok->uid);
		
		if (!pathos_smtp_mail($u->email,"Password Manager <passwords@pathos>","Your New Password",$msg)) {
			echo "Error sending confirmation message.  Contact an administrator.";
		} else {
			// Save new password
			$u->password = md5($newpass);
			pathos_users_saveUser($u);
			
			$db->delete("passreset_token","uid=".$tok->uid);
			
			echo "Your new password has been emailed to you.";
		}
	}
// END PERM CHECK

?>