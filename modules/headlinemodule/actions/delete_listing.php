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
# $Id: delete_headline.php,v 1.2 2005/02/19 16:53:35 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

	$headline = null;		
	if (isset($_GET['id'])) {
		$headline = $db->selectObject('headline', 'id='.$_GET['id']);
		if ($headline != null) {
			$loc = unserialize($headline->location_data);
		}
	}
	
	if ($headline) {
		$loc = unserialize($headline->location_data);
		if (exponent_permissions_check("manage",$loc)) {
			$db->delete('headline', 'id='.$_GET['id']);
			exponent_flow_redirect();
		} else {
			echo SITE_403_HTML;
		}
	} else {
		echo SITE_404_HTML;
	}

?>