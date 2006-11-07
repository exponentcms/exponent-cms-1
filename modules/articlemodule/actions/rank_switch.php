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
# $Id: rank_switch.php,v 1.2 2005/02/19 16:53:34 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

if (exponent_permissions_check("manage",$loc)) {
	
	$action_a = $db->selectObject("article","location_data='".serialize($loc)."' AND category_id=".$_GET['category_id']." AND rank=".$_GET['a']);
	$action_b = $db->selectObject("article","location_data='".serialize($loc)."' AND category_id=".$_GET['category_id']." AND rank=".$_GET['b']);
	
	$action_a->rank = $_GET['b'];
	$action_b->rank = $_GET['a'];
	
	$db->updateObject($action_a,"article");
	$db->updateObject($action_b,"article");
	
	exponent_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>