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
# $Id: class.php,v 1.6 2005/05/09 05:43:56 filetreefrog Exp $
##################################################

class articlemodule {
	function name() { return "Article Module"; }
	function description() { return "Manages Articles"; }
	function author() { return "Adam Kessler"; }
	
	function hasSources() { return true; }
	function hasContent() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = "") {
		return array(
			'administrate'=>'Administrate',
			'configure'=>'Configure',
			'manage'=>'Manage Articles',
		);
	}
		
	function show($view,$loc = null, $title = "") {
		global $db;
		
		// Used later, for recalculation and other things.
		if (!defined("SYS_SORTING")) require_once(BASE."subsystems/sorting.php");
		
		$config = $db->selectObject('articlemodule_config',"location_data='".serialize($loc)."'");
		if ($config == null) {
			$config->enable_categories = 0;
			$config->recalc = 0; // No need to recalculate, no categories
		} else  if ($config->recalc == 1) {
			// We need to recaculate the rankings.
			if ($config->enable_categories == 1) {
				// Recalc, keeping in mind the category structure.
				$cats = $db->selectObjects("category","location_data='".serialize($loc)."'");
				$c = null;
				$c->id = 0;
				$cats[] = $c;
				foreach ($cats as $c) {
					// Loop over each category.
					$rank = 0;
					foreach ($db->selectObjects("article","location_data='".serialize($loc)."' AND category_id=".$c->id) as $article) {
						$article->rank = $rank;
						$db->updateObject($article,"article");
						$rank++;
					}
				}
			} else {
				// Recaculate blindly, ignoring categories.
				$articles = $db->selectObjects("article","location_data='".serialize($loc)."'");
				usort($articles, "exponent_sorting_byRankAscending");
				$rank = 0;
				foreach ($articles as $article) {
					$article->rank = $rank;
					$article->category_id = 0;
					$db->updateObject($article,"article");
					$rank++;
				}
			}
			$config->recalc = 0;
			$db->updateObject($config,'articlemodule_config',"location_data='".serialize($loc)."'");
		}
		
		// Create the template
		$template = new template("articlemodule",$view,$loc);
		$template->assign("config",$config);
		
		// Get all of the categories for this Article module:
		$cats = array();
		$cats = $db->selectObjectsIndexedArray('category', "location_data='".serialize($loc)."'");
		if ($config->enable_categories) {
			if (count($cats) != 0) {
				$template->assign('hasCategories', 1);				
			} else {
				$template->assign('hasCategories', 0);
			}
		} else {
			$template->assign('hasCategories', 0);
		}
		
		$c->name = '';
		$c->id = 0;
		uasort($cats, "exponent_sorting_byRankAscending");
		$cats[0] = $c;
		$template->assign('categories', $cats);
		
		$data = array();
		if ($config->enable_categories == true) {
			foreach ($cats as $id=>$c) {
				//Get all the questions & answers for this article module. ($qnas stands for "Questions 'n Answers")
				$tmp = $db->selectObjects("article","location_data='".serialize($loc)."' AND category_id=".$id);
				$catids = array_keys($cats); // for in_array check only
				for ($i = 0; $i < count($tmp); $i++) {
					if (!in_array($tmp[$i]->category_id,$catids)) {
						echo 'Setting cat id to 0<br />';
						$tmp[$i]->category_id = 0;
					}
				}
				usort($tmp, "exponent_sorting_byRankAscending");
				$data[$id] = $tmp;
			}
		} else {
			$tmp = $db->selectObjects("article","location_data='".serialize($loc)."' AND category_id=0");
			usort($tmp, 'exponent_sorting_byRankAscending');
			$data[0] = $tmp;
		}
		
		$template->assign('data',$data);
		$template->register_permissions(
			array('administrate','configure','manage'),
			$loc);
		$template->assign('moduletitle', $title);
		//echo "<xmp>";
		//print_r ($data);
		//echo "</xmp>";
		$template->output();
	}
	
	function deleteIn($loc) {
		global $db;
		$db->delete('article',"location_data='".serialize($loc)."'");
		$db->delete('category', "location_data='".serialize($loc)."'");
		$db->delete('articlemodule_config', "location_data='".serialize($loc)."'");
	}
	
	function copyContent($oloc,$nloc) {
		global $db;
		$catid_map = array();
		
		foreach ($db->selectObjects('category',"location_data='".serialize($oloc)."'") as $category) {
			$oldid = $category->id;
			unset($category->id);
			$category->location_data = serialize($nloc);
			$catid_map[$oldid] = $db->insertObject($category,'category');
		}
		
		foreach ($db->selectObjects('article',"location_data='".serialize($oloc)."'") as $object) {
			unset($object->id);
			$object->location_data = serialize($nloc);
			if (isset($catid_map[$object->category_id])) {
				$object->category_id = $catid_map[$object->category_id];
			} else {
				$object->category_id = 0;
			}
			$db->insertObject($object,'article');
		}
		
		
		$conf = $db->selectObject('articlemodule_config', "location_data='".serialize($oloc)."'");
		unset($conf->id);
		$conf->location_data = serialize($nloc);
		$db->insertObject($conf,'articlemodule_config');
	}

	function searchName() {
                return "Articles";
        }
	
	function spiderContent($item = null) {
		global $db;
		
		if (!defined('SYS_SEARCH')) require_once(BASE.'subsystems/search.php');
		
		$search = null;
		$search->category = 'Articles';
		$search->ref_module = 'articlemodule';
		$search->ref_type = 'article';
		
		if ($item) {
			$db->delete('search',"ref_module='articlemodule' AND ref_type='article' AND original_id=" . $item->id);
			$search->original_id = $item->id;
			$search->title = ' ' . $item->title . ' ';
			$search->view_link = 'index.php?module=articlemodule&action=view_article&id='.$item->id;
			$search->body = ' ' . exponent_search_removeHTML($item->body) . ' ';
			$search->location_data = $item->location_data;
			$db->insertObject($search,'search');
		} else {
			$db->delete('search',"ref_module='articlemodule' AND ref_type='article'");
			foreach ($db->selectObjects('article') as $item) {
				$search->original_id = $item->id;
				$search->title = ' ' . $item->title . ' ';
				$search->view_link = 'index.php?module=articlemodule&action=view_article&id='.$item->id;
				$search->body = ' ' . exponent_search_removeHTML($item->body) . ' ';
				$search->location_data = $item->location_data;
				$db->insertObject($search,'search');
			}
		}
		
		return true;
	}
}

?>
