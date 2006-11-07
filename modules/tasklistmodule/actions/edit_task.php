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
# $Id: edit_task.php,v 1.1 2005/02/22 16:43:35 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

$task = null;
if (isset($_GET['id'])) {
	$task = $db->selectObject('tasklist_task','id='.$_GET['id']);
	if ($task) {
		$loc = unserialize($task->location_data);
	}
}

if (($task && exponent_permissions_check('edit',$loc)) || (!$task && exponent_permissions_check('create',$loc))) {
	$form = tasklist_task::form($task);
	$form->location($loc);
	$form->meta('action','save_task');
	
	$template = new template('tasklistmodule','_form_editTask');
	$template->assign('form_html',$form->toHTML());
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>