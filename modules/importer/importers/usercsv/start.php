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
	
	//Get the I18N constants
	pathos_lang_loadDictionary('importers', 'usercsv');
	//Setup the mete data (hidden values)
	$form = new form();
	$form->meta("module","importer");
	$form->meta("action","page");
	$form->meta("page","mapper");
	$form->meta("importer","usercsv");

	//Setup the arrays with the name/value pairs for the dropdown menus
	$delimiterArray = Array(
		','=>TR_IMPORTER_USERCSV_DEMILITER_ARRAY_COMMA_KEY,
		';'=>TR_IMPORTER_USERCSV_DEMILITER_ARRAY_SEMICOLON_KEY,
		':'=>TR_IMPORTER_USERCSV_DEMILITER_ARRAY_COLON_KEY,
		' '=>TR_IMPORTER_USERCSV_DEMILITER_ARRAY_SPACE_KEY);

	//Register the dropdown menus
	$form->register("delimiter", TR_IMPORTER_USERCSV_DEMILITER, New dropdowncontrol(",", $delimiterArray));
	$form->register("upload", TR_IMPORTER_USERCSV_UPLOAD, New uploadcontrol());
	$form->register("rowstart", TR_IMPORTER_USERCSV_ROWSTART, New textcontrol("1",1,0,6));
	$form->register("submit", "", New buttongroupcontrol(TR_IMPORTER_USERCSV_SUBMIT,"", TR_IMPORTER_USERCSV_CANCEL));
	$template->assign("form_html",$form->tohtml());
	$template->output();
	pathos_forms_cleanup();
}
?>
