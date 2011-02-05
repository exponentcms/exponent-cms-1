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

	$qna = null;		
	if (isset($_POST['categories'])) {
		$cat = $_POST['categories'];
	} else {
		$cat = 0;
	}
	if (isset($_POST['id'])) {
		$qna = $db->selectObject('faq', 'id='.$_POST['id']);
		if ($qna != null) {
			$loc = unserialize($qna->location_data);
		} 
	} else {
		$qna->rank = $db->max('faq', 'rank', 'location_data', "location_data='".serialize($loc)."' AND category_id=".$cat);
		if ($qna->rank == null) {
			$qna->rank = 0;
		} else { 
			$qna->rank += 1;
		}
	}
	
	if (exponent_permissions_check("manage",$loc)) {
		$oldcatid = $qna->category_id;
		$qna = faq::update($_POST, $qna);
		$qna->location_data = serialize($loc);
		$qna->category_id = $cat;
		if (($oldcatid != $qna->category_id) && isset($qna->id)) {
			$db->decrement('faq', 'rank', 1, "location_data='".serialize($loc)."' AND rank > ".$qna->rank." AND category_id=".$oldcatid);
			$qna->rank = $db->max('faq', 'rank', 'location_data', "location_data='".serialize($loc)."' AND category_id=".$qna->category_id);
			if ($qna->rank == null) {
				$qna->rank = 0;
			} else { 
				$qna->rank += 1;
			}
		}
		if (isset($qna->id)) {
			$db->updateObject($qna,"faq");
		} else {
			$db->insertObject($qna,"faq");
		}		
		faqmodule::spiderContent($qna);
		exponent_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
	
?>
