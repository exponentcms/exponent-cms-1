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
	if (isset($_POST['d'])) {
		if (!defined("SYS_WORKFLOW")) require_once(BASE."subsystems/workflow.php");
		
		foreach (array_keys($_POST['d']) as $id) {
			$rev = $db->selectObject($_POST['datatype']."_wf_revision","id=$id");
			if ($rev->wf_minor == 0) {
				$db->delete($_POST['datatype']."_wf_revision","wf_original=".$rev->wf_original." AND wf_major=".($rev->wf_major-1) . " AND wf_minor != 0");
			}
			$db->delete($_POST['datatype']."_wf_revision","id=".$rev->id);
		}
		pathos_flow_redirect();
	}
} else {
	echo SITE_403_HTML;
}

?>