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
# $Id: edit_article.php,v 1.4 2005/04/26 02:50:20 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");
	$article = null;
	if (isset($_GET['id'])) {
		$article = $db->selectObject("article","id=".$_GET['id']);
		if ($article != null) {
			$loc = unserialize($article->location_data);
		} else {
			echo SITE_404_HTML;
		}
	}
	
	if (!$article) {
		$article->category_id = 0;
	}
	
	if (exponent_permissions_check("manage",$loc)) {
		$config = $db->selectObject('articlemodule_config',"location_data='".serialize($loc)."'");
		if ($config == null) {
			$config->enable_categories = 0;
		}
		$form = article::form($article);
		$form->location($loc);
		$form->meta("action","save_article");
				
		$template = new template("articlemodule","_form_editarticle",$loc);
		if ($config->enable_categories) {
			$allcats = $db->selectObjects('category', "location_data='".serialize($loc)."'");
			if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
			usort($allcats, "exponent_sorting_byRankAscending");
			$catarray = array();
			foreach ($allcats as $cat) {
				$catarray[$cat->id] = $cat->name;
			}			
			$form->registerBefore('title', 'categories', 'Select Category', new dropdowncontrol($article->category_id, $catarray));
		}
		$template->assign("is_edit",(isset($qna->id) ? 1 : 0));
		$template->assign("form_html",$form->toHTML());
		$template->output();
	} else {
		echo SITE_403_HTML;
	}
	
?>