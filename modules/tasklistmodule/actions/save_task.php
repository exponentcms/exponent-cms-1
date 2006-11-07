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
# $Id: save_task.php,v 1.2 2005/03/13 19:02:20 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

$task = null;
if (isset($_POST['id'])) {
	$task = $db->selectObject('tasklist_task','id='.$_POST['id']);
	if ($task) {
		$loc = unserialize($task->location_data);
	}
}

if (($task && exponent_permissions_check('edit',$loc)) || (!$task && exponent_permissions_check('create',$loc))) {	
	$task = tasklist_task::update($_POST,$task);
	$task->location_data = serialize($loc);
	
	if (isset($task->id)) {
		$db->updateObject($task,'tasklist_task');
	} else {
		$db->insertObject($task,'tasklist_task');
	}
	
	exponent_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>