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
# $Id: save.php,v 1.1 2005/03/13 19:17:05 filetreefrog Exp $
##################################################

if (!defined('EXPONENT')) exit('');

$link = null;
if (isset($_POST['id'])) {
	$link = $db->selectObject('linklist_link','id='.$_POST['id']);
	if ($link) {
		$loc = unserialize($link->location_data);
	}
}

if (($link && exponent_permissions_check('edit',$loc)) || (!$link && exponent_permissions_check('create',$loc))) {
	$link = linklist_link::update($_POST,$link);
	$link->location_data = serialize($loc);
	
	if (isset($link->id)) {
		$db->updateObject($link,'linklist_link');
	} else {
		$db->insertObject($link,'linklist_link');
	}
	exponent_flow_redirect();
} else {
	echo SITE_404_HTML;
}

?>