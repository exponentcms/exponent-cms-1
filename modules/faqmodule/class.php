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
# $Id: class.php,v 1.9 2005/05/12 19:39:46 filetreefrog Exp $
##################################################

class faqmodule {
	function name() { return 'Frequently Asked Questions'; }
	function description() { return 'Manages Frequently Asked Questions'; }
	function author() { return 'Adam Kessler'; }
	
	function hasSources() { return true; }
	function hasContent() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = "") {
		return array(
			'administrate'=>'Administrate',
			'configure'=>'Configure',
			'manage'=>'Manage FAQs'
		);
	}
		
	function show($view,$loc = null, $title = "") {
		global $db;
		
		// Used later, for recalculation and other things.
		if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
		
		$config = $db->selectObject('faqmodule_config',"location_data='".serialize($loc)."'");
		if ($config == null) {
			$config->enable_categories = 0;
			$config->recalc = 0; // No need to recalculate, no categories
		} else  if ($config->recalc == 1) {
			// We need to recaculate the rankings.
			if ($config->enable_categories == 1) {
				// Recalc, keeping in mind the category structure.
				$cats = $db->selectObjects('category',"location_data='".serialize($loc)."'");
				$c = null;
				$c->id = 0;
				$cats[] = $c;
				foreach ($cats as $c) {
					// Loop over each category.
					$rank = 0;
					foreach ($db->selectObjects('faq',"location_data='".serialize($loc)."' AND category_id=".$c->id) as $qna) {
						$qna->rank = $rank;
						$db->updateObject($qna,'faq');
						$rank++;
					}
				}
			} else {
				// Recaculate blindly, ignoring categories.
				$qnas = $db->selectObjects('faq',"location_data='".serialize($loc)."'");
				usort($qnas, 'exponent_sorting_byRankAscending');
				$rank = 0;
				foreach ($qnas as $qna) {
					$qna->rank = $rank;
					$qna->category_id = 0;
					$db->updateObject($qna,'faq');
					$rank++;
				}
			}
			$config->recalc = 0;
			$db->updateObject($config,'faqmodule_config',"location_data='".serialize($loc)."'");
		}
		
		// Create the template
		$template = new template("faqmodule",$view,$loc);
		$template->assign("config",$config);

		$i18n = exponent_lang_loadFile('modules/faqmodule/class.php');
		
		// Get all of the categories for this FAQ module:
		$cats = array();
		$cats = $db->selectObjectsIndexedArray('category', "location_data='".serialize($loc)."'");
		if ($config->enable_categories) {
			#$cats = $db->selectObjects('category', "location_data='".serialize($loc)."'");
			if (count($cats) != 0) {
				$template->assign('hasCategories', 1);				
			} else {
				$template->assign('hasCategories', 0);
			}
		} else {
			$template->assign('hasCategories', 0);
		}
		
//		$c->name = $i18n['no_category'];
		$c->name = '';
		$c->id = 0;
		$c->rank = -1;
		$c->color = "#000000";
		$cats[0] = $c;
		uasort($cats, "exponent_sorting_byRankAscending");
		$template->assign('categories', $cats);
		
		$data = array();
		if ($config->enable_categories == true) {
			foreach ($cats as $id=>$c) {
				//Get all the questions & answers for this FAQ module. ($qnas stands for "Questions 'n Answers")
				$tmp = $db->selectObjects("faq","location_data='".serialize($loc)."' AND category_id=".$id);
				usort($tmp, "exponent_sorting_byRankAscending");
				$data[$id] = $tmp;
			}
		} else {
//			$tmp = $db->selectObjects("faq","location_data='".serialize($loc)."' AND category_id=0");
			$tmp = $db->selectObjects("faq","location_data='".serialize($loc)."'");
			usort($tmp, "exponent_sorting_byRankAscending");
			$data[0] = $tmp;
		}

    	$template->assign("qnalist", $data[0]);  
		$template->assign("data",$data);
		$template->register_permissions(array("administrate","configure", "manage"),$loc);
		$template->assign("moduletitle", $title);
		//echo "<xmp>";
		//print_r ($data);
		//echo "</xmp>";
		$template->output();
	}
	
	function deleteIn($loc) {
		global $db;
		$db->delete('faq',"location_data='".serialize($loc)."'");
		$db->delete('category', "location_data='".serialize($loc)."'");
		$db->delete('faqmodule_config', "location_data='".serialize($loc)."'");
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
		
		foreach ($db->selectObjects('faq',"location_data='".serialize($oloc)."'") as $object) {
			unset($object->id);
			$object->location_data = serialize($nloc);
			if (isset($catid_map[$object->category_id])) {
				$object->category_id = $catid_map[$object->category_id];
			} else {
				$object->category_id = 0;
			}
			$db->insertObject($object,'faq');
		}
		
		
		$conf = $db->selectObject('faqmodule_config', "location_data='".serialize($oloc)."'");
		unset($conf->id);
		$conf->location_data = serialize($nloc);
		$db->insertObject($conf,'faqmodule_config');
	}

	function searchName() {
		return "Frequently Asked Questions";
	}	

	function spiderContent($item = null) {
		global $db;
		
		if (!defined('SYS_SEARCH')) require_once(BASE.'subsystems/search.php');
		
		$search = null;
		$search->category = 'Frequently Asked Questions';
		$search->ref_module = 'faqmodule';
		$search->ref_type = 'faq';
		$search->view_link = ''; // This is fine, since we are guaranteed to be able to view the FAQ from the section.
		
		if ($item) {
			$db->delete('search',"ref_module='faqmodule' AND ref_type='faq' AND original_id=" . $item->id);
			$search->original_id = $item->id;
			$search->title = ' ' . $item->question . ' ';
			$search->body = ' ' . exponent_search_removeHTML($item->answer) . ' ';
			$search->location_data = $item->location_data;
			$db->insertObject($search,'search');
		} else {
			$db->delete('search',"ref_module='faqmodule' AND ref_type='faq'");
			foreach ($db->selectObjects('faq') as $item) {
				$search->original_id = $item->id;
				$search->title = ' ' . $item->question . ' ';
				$search->body = ' ' . exponent_search_removeHTML($item->answer) . ' ';
				$search->location_data = $item->location_data;
				$db->insertObject($search,'search');
			}
		}
		
		return true;
	}
}

?>
