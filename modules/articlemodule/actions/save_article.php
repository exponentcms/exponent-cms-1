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
# $Id: save_article.php,v 1.4 2005/03/18 02:58:26 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

	$article = null;		
	if (isset($_POST['id'])) {
		$article = $db->selectObject('article', 'id='.$_POST['id']);
		if ($article != null) {
			$loc = unserialize($article->location_data);
		} 
	} else {
		$article->rank = $db->max('article', 'rank', 'location_data', "location_data='".serialize($loc)."'");
		if ($article->rank == null) {
			$article->rank = 0;
		} else { 
			$article->rank += 1;
		}
	}
	
	if (exponent_permissions_check("manage",$loc)) {
		$oldcatid = $article->category_id;
		$article = article::update($_POST, $article);
		$article->location_data = serialize($loc);
		if (isset($_POST['categories'])) {
			$article->category_id = $_POST['categories'];
		}
		if (isset($article->id)) {
			$article->id = $db->updateObject($article,"article");
		} else {
			$article->id = $db->insertObject($article,"article");
		}
		
		articlemodule::spiderContent($article);
		
		if ($oldcatid != $article->category_id) {
			$db->decrement('article', 'rank', 1, "location_data='".serialize($loc)."' AND rank > ".$article->rank." AND category_id=".$article->category_id);
		}
		
		exponent_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
	

?>