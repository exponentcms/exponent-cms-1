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
	if (!defined("SYS_USERS")) include_once(BASE."subsystems/users.php");
	$u = pathos_users_getUserByName($_POST['username']);
	
	if ($u != null && $u->is_admin == 0 && $u->email != "") {
		if (!defined("SYS_SMTP")) include_once(BASE."subsystems/smtp.php");
		
		md5(time()).uniqid("");
		$tok = null;
		$tok->uid = $u->id;
		$tok->expires = time() + 2*3600;
		$tok->token = md5(time()).uniqid("");;
		
		$template = new template("loginmodule","_email_resetconfirm",$loc);
		$template->assign("token",$tok);
		$msg = $template->render();
		
		if (!pathos_smtp_mail($u->email,"Password Manager <password@pathos>","Password Reset Confirmation",$msg)) {
			echo "Error sending confirmation message.";
		} else {
			$db->insertObject($tok,"passreset_token");
			echo "Confirmation message sent.";
		}
	} else {
		echo "Your password cannot be reset.  Please contact an administrator.";
	}
// END PERM CHECK

?>