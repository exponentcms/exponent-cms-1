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

$i18n = exponent_lang_loadFile('modules/loginmodule/actions/resetpass_send.php');

if (!defined('SYS_USERS')) require_once(BASE.'subsystems/users.php');
$u = exponent_users_getUserByName($_POST['username']);

if ($u != null && $u->is_acting_admin == 0 && $u->is_admin == 0 && $u->email != '') {
	if (!defined('SYS_SMTP')) require_once(BASE.'subsystems/smtp.php');
	
	$tok = null;
	$tok->uid = $u->id;
	$tok->expires = time() + 2*3600;
	$tok->token = md5(time()).uniqid('');;
	
	$e_template = new template('loginmodule','_email_resetconfirm',$loc);
	$e_template->assign('token',$tok);
	$msg = $e_template->render();
	
	// FIXME: smtp call prototype / usage has changed.
	if (!exponent_smtp_mail($u->email,$i18n['from_name'].' <'.$i18n['from_email'].'@'.HOSTNAME.'>',$i18n['title'],$msg)) {
		echo $i18n['smtp_error'];
	} else {
		$db->insertObject($tok,'passreset_token');
		echo $i18n['sent'];
	}
} else {
	echo $i18n['unable'];
}

?>