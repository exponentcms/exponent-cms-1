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

if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
if (!defined("SYS_FILES")) include_once(BASE."subsystems/files.php");

$template = New template("importer", "_usercsv_form_start");

if (pathos_files_canCreate(BASE."modules/importer/importers/usercsv/tmp/test") != SYS_FILES_SUCCESS) {
	$template->assign("error", "The modules/importer/importers/usercsv/tmp directory is not writable.  Please contact your administrator.");
	$template->output();
}else{
	//initialize the for stuff
	pathos_forms_initialize();
	//Setup the mete data (hidden values)
	$form = new form();
	$form->meta("module","importer");
	$form->meta("action","page");
	$form->meta("page","mapper");
	$form->meta("importer","usercsv");

	//Setup the arrays with the name/value pairs for the dropdown menus
	$delimiterArray = Array(
		","=>"Comma",
		";"=>"Semicolon",
		":"=>"Colon",
		" "=>"Space");

	//Register the dropdown menus
	#$form->register("unameOptions","User Name Generations Options", New dropdowncontrol("INFILE", $userNameOptionsArray));
	$form->register("delimiter", "Delimiter Character", New dropdowncontrol(",", $delimiterArray));
	$form->register("upload", "CSV file to upload", New uploadcontrol());
	$form->register("rowstart", "Start reading data from row number", New textcontrol("1",1,0,6));
	$form->register("submit", "", New buttongroupcontrol("Submit","", "Cancel"));
	$template->assign("form_html",$form->tohtml());
	$template->output();
	pathos_forms_cleanup();
}
?>
