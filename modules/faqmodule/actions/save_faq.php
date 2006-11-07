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
# $Id: save_faq.php,v 1.4 2005/02/19 16:53:35 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

	$qna = null;		
	if (isset($_POST['id'])) {
		$qna = $db->selectObject('faq', 'id='.$_POST['id']);
		if ($qna != null) {
			$loc = unserialize($qna->location_data);
		} 
	} else {
		$qna->rank = $db->max('faq', 'rank', 'location_data', "location_data='".serialize($loc)."'");
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
		if (isset($_POST['categories'])) {
			$qna->category_id = $_POST['categories'];
		}
		if (isset($qna->id)) {
			$db->updateObject($qna,"faq");
		} else {
			$db->insertObject($qna,"faq");
		}		
		
		if ($oldcatid != $qna->category_id) {
			$db->decrement('faq', 'rank', 1, "location_data='".serialize($loc)."' AND rank > ".$qna->rank." AND category_id=".$qna->category_id);
		}
		
		exponent_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
	

?>