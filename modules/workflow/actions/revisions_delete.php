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

if (!defined("PATHOS")) exit("");

$rloc = pathos_core_makeLocation($_GET['m'],$_GET['s']);
if (pathos_permissions_check("manage_approval",$rloc)) {
	if (isset($_POST['d'])) {
		if (!defined("SYS_WORKFLOW")) include_once(BASE."subsystems/workflow.php");
		
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