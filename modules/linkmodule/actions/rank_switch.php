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
# $Id: rank_switch.php,v 1.2 2005/02/19 16:53:35 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

if (exponent_permissions_check("configure",$loc)) {

//	$db->switchValues('link', 'rank', intval($_GET['a']), intval($_GET['b']), "location_data='".serialize($loc)."'");
	
	$object_a = $db->selectObject('link',"rank='".$_GET['a']."' AND location_data='".serialize($loc)."'");
	$object_b = $db->selectObject('link',"rank='".$_GET['b']."' AND location_data='".serialize($loc)."'");

	if ($object_a && $object_b) {
		$db->switchValues('link','rank',$_GET['a'],$_GET['b'],"location_data='".serialize($loc)."'");
	} else {
		if ($object_a) {
			$object_a->rank = $_GET['b'];
			$db->updateObject($object_a,'link');
		}
		if ($object_b) {
			$object_b->rank = $_GET['a'];
			$db->updateObject($object_b,'link');
		}
	}	
	exponent_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>
