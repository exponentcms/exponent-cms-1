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

if (!defined('PATHOS')) exit('');

$i18n = pathos_lang_loadFile('modules/loginmodule/actions/login.php');

if (!defined('SYS_USERS')) require_once('subsystems/users.php');
pathos_users_login($_POST['username'],$_POST['password']);

if (!isset($_SESSION[SYS_SESSION_KEY]['user'])) {
	echo $i18n['login_error'];
} else {
	pathos_flow_redirect();
}

?>