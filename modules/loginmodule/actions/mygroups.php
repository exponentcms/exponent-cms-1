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

if (!defined('PATHOS')) exit('');

pathos_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);

$template = new Template('administrationmodule','_groupmanager',$loc);
if (!defined('SYS_USERS')) include_once(BASE.'subsystems/users.php');
$groups = array();
foreach ($db->selectObjects('groupmembership','member_id='.$user->id.' AND is_admin=1') as $memb) {
	$groups[] = $db->selectObject('group','id='.$memb->group_id);
}
$template->assign('groups',$groups);
$template->assign('perm_level',1); // So we don't get the edit/delete links.
$template->output();

?>