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

//Sanity check
if (!defined("PATHOS")) exit("");
if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");

//Get the post data for future massaging
$post = $_POST;

//Check to make sure the user filled out the required input.
if (!is_numeric($_POST["rowstart"])){
	unset($post['rowstart']);
	$post['_formError'] = "The starting row must be a number.";
	pathos_sessions_set("last_POST",$post);
	header("Location: " . $_SERVER['HTTP_REFERER']);
	exit("");
}

//Set the temp directory to put the uploaded file
$directory = "modules/importer/importers/usercsv/tmp";

//Get the file save it to the temp directory
if ($_FILES["upload"]["error"] == UPLOAD_ERR_OK) {
	$file = file::update("upload",$directory,null,time()."_".$_FILES['upload']['name']);
	if ($file == null) {
		switch($_FILES["upload"]["error"]) {
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				$post['_formError'] = "The file you attempted to upload is too large.  Contact your system administrator if this is a problem.";
				break;
			case UPLOAD_ERR_PARTIAL:
				$post['_formError'] = "The file was only partially uploaded.";
				break;
			case UPLOAD_ERR_NO_FILE:
				$post['_formError'] = "No file was uploaded.";
				break;
			default:
				$post['_formError'] = "A strange internal error has occured.  Please contact the Exponent Developers.";
				break;
		}
		pathos_sessions_set("last_POST",$post);
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit("");
	}
}

if (mime_content_type(BASE.$directory."/".$file->filename) != "text/plain"){
	$post['_formError'] = "File is not a delimited text file.";
	pathos_sessions_set("last_POST",$post);
	header("Location: " . $_SERVER['HTTP_REFERER']);
	exit("");
}

//split the line into its columns
$fh = fopen(BASE.$directory."/".$file->filename,"r");
for ($x=0; $x<$_POST["rowstart"]; $x++){
	$lineInfo = fgetcsv($fh, 2000, $_POST["delimiter"]);
}

//Build the array for the select control
$colNames = array(
	"none"=>"--Disregard this column--",
	"username"=>"Username",
	"password"=>"Password",
	"firstname"=>"First Name",
	"lastname"=>"Last Name",
	"email"=>"Email Address");

//Check to see if the line got split, otherwise throw an error
if (count($lineInfo) == 1) {
	$post['_formError'] = "This file does not appear to be delimited by \"".$_POST["delimiter"]."\".<br>Please specify a different delimiter<br><br>"; 
	pathos_sessions_set("last_POST",$post);
	header("Location: " . $_SERVER['HTTP_REFERER']);
	exit("");
}else{
	//initialize the for stuff
	pathos_forms_initialize();
	//Setup the mete data (hidden values)
	$form = new form();
	$form->meta("module","importer");
	$form->meta("action","page");
	$form->meta("page","process");
	$form->meta("rowstart", $_POST["rowstart"]);
	$form->meta("importer","usercsv");
	$form->meta("filename",$directory."/".$file->filename);
	$form->meta("delimiter",$_POST["delimiter"]); 
	for ($i=0; $i< Count($lineInfo); $i++) {
		$form->register("column[$i]", $lineInfo[$i], new dropdowncontrol("none", $colNames));
	}
	$form->register("submit", "", New buttongroupcontrol("Submit","", "Cancel"));
	$template = New Template("importer", "_usercsv_form_mapping");
	$template->assign("form_html", $form->tohtml());
	$template->output();
	pathos_forms_cleanup();
}

?>
