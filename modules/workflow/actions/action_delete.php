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

if (!defined("PATHOS")) exit("");

if (pathos_permissions_check('workflow',pathos_core_makeLocation('administrationmodule'))) {
#if ($user && $user->is_acting_admin) {
	$action = $db->selectObject("workflowaction","id=".$_GET['id']);
	$db->delete("workflowaction","id=".$action->id);
	$db->decrement("workflowaction","rank",1,"rank >= " . $action->rank . " AND policy_id=".$action->policy_id . " AND type=".$action->type);

	pathos_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>