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

if (!defined("PATHOS")) exit("");

if ($user && $user->is_admin == 1) {
	if (!defined("SYS_USERS")) include_once(BASE."subsystems/users.php");
	if (!defined("SYS_FORMS")) include_once("subsystems/forms.php");
	pathos_forms_initialize();

	$u = pathos_users_getUserById($_GET['id']);
	$u = pathos_users_getFullProfile($u);
	$form = pathos_users_form($u);
	$form->meta("module","administrationmodule");
	$form->meta("action","umgr_saveuser");
	
	$template = new template("administrationmodule","_umgr_editprofile",$loc);
	$template->assign("form_html",$form->toHTML());
	$template->assign("is_edit",isset($u->id)?1:0);
	$template->output();
	
	pathos_forms_cleanup();

} else {
	echo SITE_403_HTML;
}

?>