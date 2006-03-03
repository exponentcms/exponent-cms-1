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

if (!defined("EXPONENT")) exit("");

if (exponent_permissions_check('workflow',exponent_core_makeLocation('administrationmodule'))) {
	$action = $db->selectObject('workflowaction','id='.intval($_GET['id']));
	$db->delete('workflowaction','id='.$action->id);
	$db->decrement('workflowaction','rank',1,'rank >= ' . $action->rank . ' AND policy_id='.$action->policy_id . ' AND type='.$action->type);

	exponent_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>