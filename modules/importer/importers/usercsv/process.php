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

//Sanity Check
if (!defined("PATHOS")) exit("");
if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
//Create a new form object
pathos_forms_initialize();
$form = new form();
//Setup the mete data (hidden values)


$form->meta("module","importer");
$form->meta("action","page");
$form->meta("page","displayusers");
$form->meta("importer","usercsv");
$form->meta("column", $_POST["column"]);
$form->meta("delimiter", $_POST["delimiter"]);
$form->meta("filename", $_POST["filename"]);
$form->meta("rowstart", $_POST["rowstart"]);

if (in_array("username",$_POST["column"]) == false){
	$unameOptions = array(
               	"FILN"=>"First Initial / Last Name",
                "FILNNUM"=>"First Initial / Last Name / Random Number",
       	        "EMAIL"=>"Email Address",
               	"FNLN"=>"First Name / Last Name");
}else{
	$unameOptions = array("INFILE"=>"Username Specified in CSV File");
}

if (in_array("password", $_POST["column"]) == false){
	$pwordOptions = array(
		"RAND"=>"Generate Random Passwords",
		"DEFPASS"=>"Use the Default Password Supplied Below");
}else{
	$pwordOptions = array("INFILE"=>"Password Specified in CSV File");
}

if (Count($pwordOptions) == 1){
	$disabled = true;
}else{
	$disabled = false;
}

$form->register("unameOptions","User Name Generations Options", New dropdowncontrol("INFILE", $unameOptions));
$form->register("pwordOptions", "Password Generation Options", New dropdowncontrol("defpass", $pwordOptions));
$form->register("pwordText", "Default Password", New textcontrol("", 10, $disabled));
$form->register("update", "Update users already in database", New checkboxcontrol(0, 0));
$form->register("submit", "", New buttongroupcontrol("Submit","", "Cancel"));
$template = New Template("importer", "_usercsv_form_geninfo");
$template->assign("form_html", $form->tohtml());
$template->output();
pathos_forms_cleanup();


?>
