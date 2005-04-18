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

//Sanity Check
if (!defined("PATHOS")) exit("");
if (!defined("SYS_FORMS")) require_once(BASE."subsystems/forms.php");
//Create a new form object
pathos_forms_initialize();

//Get the I18N stuff
pathos_lang_loadDictionary('importers', 'usercsv');
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
               	"FILN"=>TR_IMPORTER_USERCSV_FILN,
                "FILNNUM"=>TR_IMPORTER_USERCSV_FILNNUM,
       	        "EMAIL"=>TR_IMPORTER_USERCSV_EMAIL,
               	"FNLN"=>TR_IMPORTER_USERCSV_FNLN);
}else{
	$unameOptions = array("INFILE"=>TR_IMPORTER_USERCSV_UNAMEINFILE);
}

if (in_array("password", $_POST["column"]) == false){
	$pwordOptions = array(
		"RAND"=>TR_IMPORTER_USERCSV_RAND,
		"DEFPASS"=>TR_IMPORTER_USERCSV_DEFPASS);
}else{
	$pwordOptions = array("INFILE"=>TR_IMPORTER_USERCSV_PWORDINFILE);
}

if (count($pwordOptions) == 1){
	$disabled = true;
}else{
	$disabled = false;
}

$form->register("unameOptions",TR_IMPORTER_USERCSV_UNAMEOPTIONS, New dropdowncontrol("INFILE", $unameOptions));
$form->register("pwordOptions", TR_IMPORTER_USERCSV_PWORDOPTIONS, New dropdowncontrol("defpass", $pwordOptions));
$form->register("pwordText", TR_IMPORTER_USERCSV_PWORDTEXT, New textcontrol("", 10, $disabled));
$form->register("update", TR_IMPORTER_USERCSV_UPDATE, New checkboxcontrol(0, 0));
$form->register("submit", "", New buttongroupcontrol(TR_IMPORTER_USERCSV_SUBMIT,"", TR_IMPORTER_USERCSV_CANCEL));
$template = New Template("importer", "_usercsv_form_geninfo");
$template->assign("form_html", $form->tohtml());
$template->output();

?>
