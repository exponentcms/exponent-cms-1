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
# $Id: delete_task.php,v 1.2 2005/03/13 19:02:59 filetreefrog Exp $
##################################################

if (!defined('EXPONENT')) exit('');

$task = null;
if (isset($_GET['id'])) {
	$task = $db->selectObject('tasklist_task','id='.$_GET['id']);
}

if ($task) {
	$loc = unserialize($task->location_data);
	
	if (exponent_permissions_check('delete',$loc)) {
		$db->delete('tasklist_task','id='.$task->id);
		exponent_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>