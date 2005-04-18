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

if (!defined('PATHOS')) exit('');

if (isset($_POST['id'])) {
	$textitem = $db->selectObject('textitem','id='.$_POST['id']);
	if ($textitem) {
		$loc = unserialize($textitem->location_data);
	}
}

if (pathos_permissions_check('edit',$loc)) {
	$textitem = textitem::update($_POST,$textitem);
	$textitem->location_data = serialize($loc);
	pathos_template_clear();
	if (!defined('SYS_WORKFLOW')) require_once(BASE.'subsystems/workflow.php');
	pathos_workflow_post($textitem,'textitem',$loc);
} else {
	echo SITE_403_HTML;
}

?>