<?php

##################################################
#
# Copyright (c) 2004-2011 OIC Group, Inc.
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
##################################################

if (!defined("EXPONENT")) exit("");

	$article = null;		
	if (isset($_GET['id'])) {
		$article = $db->selectObject('article', 'id='.$_GET['id']);
		if ($article != null) {
			$loc = unserialize($article->location_data);
		}
	}
	
	if ($article) {
		$loc = unserialize($article->location_data);
		if (exponent_permissions_check("manage",$loc)) {
			$db->delete('article', 'id='.$_GET['id']);
			$db->decrement('article', 'rank', 1, "location_data='".serialize($loc)."' AND rank > ".$article->rank." AND category_id=".$article->category_id);
			//Delete search entries
			$db->delete('search',"ref_module='articlemodule' AND ref_type='article' AND original_id=".$article->id);		
			exponent_flow_redirect();
		} else {
			echo SITE_403_HTML;
		}
	} else {
		echo SITE_404_HTML;
	}

?>
