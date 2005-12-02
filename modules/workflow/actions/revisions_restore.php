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

if (!defined("PATHOS")) exit("");

$rloc = pathos_core_makeLocation($_GET['m'],$_GET['s']);
if (pathos_permissions_check("manage_approval",$rloc)) {
	if (!defined("SYS_WORKFLOW")) require_once(BASE."subsystems/workflow.php");
	pathos_workflow_restoreRevision($_GET['datatype'],$_GET['id'],$_GET['major']);
	pathos_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>