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
# $Id: milestone_delete.php,v 1.4 2005/02/23 23:51:17 filetreefrog Exp $
##################################################

if (!defined('EXPONENT')) exit('');

$milestone = null;
if (isset($_GET['id'])) {
	$milestone = $db->selectObject('codemap_milestone','id='.$_GET['id']);
}

if ($milestone) {
	$loc = unserialize($miletone->location_data);
	
	if (exponent_permissions_check('manage_miles',$loc)) {
		$db->delete('codemap_milestone','id='.$milestone->id);
		$db->delete('codemap_stepstones','milestone_id='.$milestone->id);
		exponent_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}