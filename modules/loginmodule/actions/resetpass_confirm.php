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

if (!defined('PATHOS')) exit('');

$template = new template('loginmodule','_resetconfirm',$loc);

$db->delete('passreset_token','expires < ' . time());
$tok = $db->selectObject('passreset_token','uid='.trim($_GET['uid'])." AND token='".trim($_GET['token']) ."'");
if ($tok == null) {
	$template->assign('state','expired');
} else {
	$newpass = '';
	for ($i = 0; $i < rand(12,20); $i++) {
		$num=rand(48,122);
		if(($num > 97 && $num < 122) || ($num > 65 && $num < 90) || ($num >48 && $num < 57)) $newpass.=chr($num);
		else $i--;
	}

	// Send message
	if (!defined('SYS_SMTP')) include_once(BASE.'subsystems/smtp.php');
	
	$e_template = new template('loginmodule','_email_resetdone',$loc);
	$e_template->assign('newpass',$newpass);
	$msg = $e_template->render();
	
	if (!defined('SYS_USERS')) include_once(BASE.'subsystems/users.php');
	$u = pathos_users_getUserById($tok->uid);
	
	if (!pathos_smtp_mail($u->email,'Password Manager <passwords@pathos>','Your New Password',$msg)) {
		$template->assign('state','smtp_error');
	} else {
		// Save new password
		$u->password = md5($newpass);
		pathos_users_saveUser($u);
		
		$db->delete('passreset_token','uid='.$tok->uid);
		
		$template->assign('state','sent');
	}
}
$template->output();

?>