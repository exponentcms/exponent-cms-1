<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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

if (!defined('SYS_USERS')) require_once('subsystems/users.php');
exponent_users_logout();
exponent_permissions_clear();
exponent_sessions_unset('uilevel');
if (isset($_SESSION['nav_cache']['kids']))
			unset($_SESSION['nav_cache']['kids']);
exponent_flow_redirect();

?>
