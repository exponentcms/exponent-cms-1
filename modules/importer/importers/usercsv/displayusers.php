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
if (!defined("SYS_USERS")) include_once(BASE."subsystems/users.php");


$file = fopen(BASE.$_POST["filename"],"r");
$post = null;
$post = $_POST;
$userinfo = null;
$userinfo->username = "";
$userinfo->firstname = "";
$userinfo->lastname = "";
$userinfo->is_admin = 0;
$userinfo->is_acting_admin = 0;
$userinfo->is_locked = 0;
$userinfo->email = '';
$userarray = array();
$usersdone = array();
$linenum = 1;

while ( ($filedata = fgetcsv($file, 2000, $_POST["delimiter"])) != false){

if ($linenum >= $post["rowstart"]){
	$i = 0;

	$userinfo->changed = "";

        foreach ($filedata as $field){
		if ($post["column"][$i] != "none"){
			$colname = $post["column"][$i];
			$userinfo->$colname = trim($field);	
		}
		$i++;
	}

	switch ($post["unameOptions"]){

	case "FILN":
		if ( ($userinfo->firstname != "") && ($userinfo->lastname != "") ) {
			$userinfo->username = str_replace(" ", "", strtolower($userinfo->firstname{0}.$userinfo->lastname));
		}else{
			$userinfo->username = "";
			$userinfo->clearpassword = "";
			$userinfo->changed = "skipped";
		}
		break;
	case "FILNNUM":
		if ( ($userinfo->firstname != "") && ($userinfo->lastname != "") ) {
			$userinfo->username = str_replace(" ", "", strtolower($userinfo->firstname{0}.$userinfo->lastname.rand(100,999)));
                }else{
			$userinfo->username = "";
			$userinfo->clearpassword = "";
                        $userinfo->changed = "skipped";
                }
                break;
	case "EMAIL":
		if ($userinfo->email != "") {
			$userinfo->username = str_replace(" ", "", strtolower($userinfo->email));
                }else{
			$userinfo->username = "";
			$userinfo->clearpassword = "";
                        $userinfo->changed = "skipped";
                }
		break;
	case "FNLN":
		if ( ($userinfo->firstname != "") && ($userinfo->lastname != "") ) {
			$userinfo->username = str_replace(" ", "",strtolower($userinfo->firstname.$userinfo->lastname));
                }else{
			$userinfo->username = "";
			$userinfo->clearpassword = "";
                        $userinfo->changed = "skipped";
                }
		break;
	case "INFILE":
		if ($userinfo->username != "") {
			$userinfo->username = str_replace(" ", "", $userinfo->username);
                }else{
			$userinfo->username = "";
			$userinfo->clearpassword = "";
                        $userinfo->changed = "skipped";
                }
		break;
	}

	if ( (!isset($userinfo->changed)) || ($userinfo->changed != "skipped")) {
		switch ($post["pwordOptions"]){

		case "RAND":
			$newpass = "";
                	for ($i = 0; $i < rand(12,20); $i++) {
                        	$num=rand(48,122);
                        	if(($num > 97 && $num < 122) || ($num > 65 && $num < 90) || ($num >48 && $num < 57)) $newpass.=chr($num);
                        	else $i--;
                	}
			$userinfo->clearpassword = $newpass;
			break;
		case "DEFPASS":
			//Check to make sure the user filled out the required input.
			//if ($_POST["pwordText"] == ""){
        		//	$post = $_POST;
			//	unset($post['pwordText']);
        		//	$post['_formError'] = TR_IMPORTER_USERCSV_NOPASS_ERROR;
        		//	pathos_sessions_set("last_POST",$post);
        		//	header("Location: " . $_SERVER['HTTP_REFERER']);
        		//	exit("");
			//}
			$userinfo->clearpassword = str_replace(" ", "", trim($_POST["pwordText"]));
			break;
		}

		$userinfo->password = md5($userinfo->clearpassword);

		$suffix = "";
		while (pathos_users_getUserByName($userinfo->username.$suffix) != null) {//username already exists
			if (isset($_POST["update"]) == 1 ) {
				if (in_array($userinfo->username, $usersdone)) {
					$suffix = rand(100,999);
					$userinfo->changed = 1;	
				}else{
					$tmp = pathos_users_getUserByName($userinfo->username.$suffix);
					$userinfo->id = $tmp->id;
					break;
				}
			}else{
				$suffix = rand(100,999);
                                $userinfo->changed = 1;
			}
		}

		$userinfo->username = $userinfo->username.$suffix;	
		$userarray[] = pathos_users_saveUser($userinfo);
		$usersdone[] = $userinfo->username;
	}else{
		$userinfo->linenum = $linenum;
		$userarray[] = $userinfo;
	}	
}
	$linenum++;
}
$template = New Template("importer", "_usercsv_display_users");
$template->assign("userarray", $userarray);
$template->output();
unlink(BASE.$_POST["filename"]);
?>
