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
 
if (!defined('PATHOS')) exit('');

if (!defined('SYS_USERS')) require_once('subsystems/users.php');
pathos_users_logout();
pathos_permissions_clear();
pathos_sessions_unset('uilevel');
pathos_flow_redirect();

?>