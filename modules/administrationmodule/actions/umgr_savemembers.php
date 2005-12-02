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

// Part of the User Management category

if (!defined('PATHOS')) exit('');

if (pathos_permissions_check('user_management',pathos_core_makeLocation('administrationmodule'))) {
	$u = $db->selectObject('user','id='.$_POST['id']);
	if ($u) {
		$db->delete('groupmembership','member_id='.$u->id);
		$memb = null;
		$memb->member_id = $u->id;
		if ($_POST['membdata'] != "") {
			foreach (explode(",",$_POST['membdata']) as $id) {
				$toks = explode(':',$id);
				$memb->group_id = $toks[0];
				$memb->is_admin = $toks[1];
				$db->insertObject($memb,'groupmembership');
			}
		}
		pathos_permissions_triggerRefresh($u);
		pathos_flow_redirect();
	} else echo SITE_404_HTML;
} else {
	echo SITE_403_HTML;
}

?>