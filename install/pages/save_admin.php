<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Written and Designed by James Hunt
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################

if (!defined('EXPONENT')) exit('');

$user = $db->selectObject('user','is_admin=1');

$user->username = $_POST['username'];
if ($user->username == '') {
	$i18n = exponent_lang_loadFile('install/pages/save_admin.php');
	echo $i18n['bad_username'];
} else {
    if (validator::validate_email_address($_POST['email']) == false) {
        flash('You must supply a valid email address.');
        header('Location: index.php?page=admin_user&erremail=true');
        exit();
    }
	$user->password = md5($_POST['password']);
	$user->firstname = $_POST['firstname'];
	$user->lastname = $_POST['lastname'];
	$user->is_admin = 1;
	$user->is_acting_admin = 1;
	$user->email = $_POST['email'];
	$user->created_on = time();
	if (isset($user->id)){
		$db->updateObject($user,'user');
	}else{
		$db->insertObject($user,'user');
	}
	
	header('Location: '.'index.php?page=final');
}

?>
