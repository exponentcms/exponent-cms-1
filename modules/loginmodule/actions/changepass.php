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
 * Change the Current User's Password
 *
 * @author James Hunt
 * @copyright 2004 James Hunt and OIC Group, Inc.
 *
 * @package Modules
 * @subpackages Login
 */
 
if (!defined("PATHOS")) exit("");

if (!defined("SYS_USERS")) include_once("subsystems/users.php");
if (!defined("SYS_FORMS")) include_once("subsystems/forms.php");
pathos_forms_initialize();

$form = new form();
$form->location($loc);
$form->meta("action","savepass");
$form->register("oldpass","Old Password",new passwordcontrol());
$form->register("pass1","New Password",new passwordcontrol());
$form->register("pass2","Confirm",new passwordcontrol());

$form->register("submit","",new buttongroupcontrol("Change"));

$template = new template("loginmodule","_form_changePassword",$loc);
$template->assign("form_html",$form->toHTML());
$template->output();

pathos_forms_cleanup();
?>