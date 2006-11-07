<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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
# $Id: update_checklist.php,v 1.2 2005/02/23 15:38:43 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

if (exponent_permissions_check('edit',$loc)) {
	$config = $db->selectObject('tasklistmodule_config',"location_data='".serialize($loc)."'");
	if ($config == null) {
		$config->show_completed = 1;
	}

	foreach ($db->selectObjects('tasklist_task',"location_data='".serialize($loc)."'") as $t) {
		if (isset($_POST['item'][$t->id])) {
			$t->completion = 100;
			$db->updateObject($t,'tasklist_task');
		} else if ($config->show_completed == 1 && $t->completion == 100) {
			$t->completion = 0;
			$db->updateObject($t,'tasklist_task');
		}
	}

	exponent_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>