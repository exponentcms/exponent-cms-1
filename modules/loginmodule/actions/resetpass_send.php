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

pathos_lang_loadDictionary('modules','loginmodule');

if (!defined('SYS_USERS')) require_once(BASE.'subsystems/users.php');
$u = pathos_users_getUserByName($_POST['username']);

$template = new template('loginmodule','_resetsend');

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
	if (!pathos_smtp_mail($u->email,'Password Manager <password@'.HOSTNAME.'>','Password Reset Confirmation',$msg)) {
		$template->assign('state','smtp_error');
	} else {
		$db->insertObject($tok,'passreset_token');
		$template->assign('state','sent');
	}
} else {
	$template->assign('state','unable');
}
$template->output();

?>