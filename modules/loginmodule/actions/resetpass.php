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

/**
 * Start the Process of resetting a password.
 *
 * @author James Hunt
 * @copyright 2004 James Hunt and OIC Group, Inc.
 *
 * @package Modules
 * @subpackages Login
 */
 
if (!defined("PATHOS")) exit("");

// PERM CHECK
	if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
	pathos_forms_initialize();
	
	$form = new form();
	$form->meta("module","loginmodule");
	$form->meta("action","resetpass_send");
	$form->register("username","Username",new textcontrol());
	$form->register("submit","",new buttongroupcontrol("Reset Password"));
	
	$template = new template("loginmodule","_form_resetpass",$loc);
	$template->assign("form_html",$form->toHTML());
	$template->output();
	
	pathos_forms_cleanup();
// END PERM CHECK

?>