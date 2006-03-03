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

if (!defined('EXPONENT')) exit('');

$i18n = exponent_lang_loadFile('modules/loginmodule/actions/resetpass_confirm.php');

$db->delete('passreset_token','expires < ' . time());
$tok = $db->selectObject('passreset_token','uid='.trim($_GET['uid'])." AND token='".preg_replace('/[^A-Za-z0-9]/','',$_GET['token']) ."'");
if ($tok == null) {
	echo $i18n['expired'];
} else {
	$newpass = '';
	for ($i = 0; $i < rand(12,20); $i++) {
		$num=rand(48,122);
		if(($num > 97 && $num < 122) || ($num > 65 && $num < 90) || ($num >48 && $num < 57)) $newpass.=chr($num);
		else $i--;
	}

	// Send message
	if (!defined('SYS_SMTP')) require_once(BASE.'subsystems/smtp.php');
	
	$e_template = new template('loginmodule','_email_resetdone',$loc);
	$e_template->assign('newpass',$newpass);
	$msg = $e_template->render();
	
	if (!defined('SYS_USERS')) require_once(BASE.'subsystems/users.php');
	$u = exponent_users_getUserById($tok->uid);
	
	if (!exponent_smtp_mail($u->email,$i18n['from_name'].' <'.$i18n['from_email'].'@'.HOSTNAME.'>',$i18n['title'],$msg)) {
		echo $i18n['smtp_error'];
	} else {
		// Save new password
		$u->password = md5($newpass);
		exponent_users_saveUser($u);
		
		$db->delete('passreset_token','uid='.$tok->uid);
		
		echo $i18n['sent'];
	}
}

?>