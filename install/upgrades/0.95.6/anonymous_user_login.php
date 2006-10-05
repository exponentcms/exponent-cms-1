<?php
##################################################
#
# Copyright (c) 2006 Maxim Mueller
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

global $db;

$myUser = new user();

$myUser->username = "anonymous";
$myUser->password = md5("anonymous");
$myUser->is_admin = 0;
$myUser->is_acting_admin = 0;
$myUser->is_locked = 0;
$myUser->firstname = "Anonymous";
$myUser->lastname = "User";
$myUser->email = "anonymous@localhost";
$myUser->created_on = time();

$db->insertObject($myUser,'user');
 
 
?>
