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

if (!defined('SYS_USERS')) require_once('subsystems/users.php');
pathos_users_login($_POST['username'],$_POST['password']);

if (!isset($_SESSION[SYS_SESSION_KEY]['user'])) {
	if (MAINTENANCE_MODE) {
		echo MAINTENANCE_MSG_HTML;
	} else {
		echo TR_LOGINMODULE_LOGINERR;
	}
} else {
	pathos_flow_redirect();
}

?>