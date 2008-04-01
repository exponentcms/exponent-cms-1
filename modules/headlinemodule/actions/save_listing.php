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
# $Id: save_headline.php,v 1.4 2005/04/12 16:06:11 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

	$headline = null;		
	if (isset($_POST['id'])) {
		$headline = $db->selectObject('headline', 'id='.$_POST['id']);
		if ($headline != null) {
			$loc = unserialize($headline->location_data);
		} 
	} 
	
	if (exponent_permissions_check("manage",$loc)) {	
		//Get the file save it to the temp directory
		$source = $loc->src;
		$headline->headline = $_POST['headline'];
		$headline->location_data = serialize($loc);
		if (isset($headline->id)) {
			$db->updateObject($headline,"headline") or die(mysql_error());
		} else {
			$db->insertObject($headline,"headline") or die(mysql_error());
		}		
		
		exponent_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
	

?>