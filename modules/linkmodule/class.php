<?php

#############################################################
# LINKMODULE
#############################################################
# Copyright (c) 2006 Eric Lestrade 
#
# Code based on Article Module for Exponent 0.96.5
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##############################################################

class linkmodule {

	function name() { return exponent_lang_loadKey('modules/linkmodule/class.php','module_name'); }
	function description() { return exponent_lang_loadKey('modules/linkmodule/class.php','module_description');  }
	function author() { return "Eric Lestrade"; }
	
	function hasSources() { return true; }
	function hasContent() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = "") {
      $i18n = exponent_lang_loadFile('modules/linkmodule/class.php');
		return array(
			'administrate'=>$i18n['perm_administrate'],
			'add'=>$i18n['perm_add'],
			'edit'=>$i18n['perm_edit'],
// put in an $i18n name
			'delete'=>'Delete Links',
//
			'import'=>$i18n['perm_import'],
			'configure'=>$i18n['perm_configure'],
       		'manage_categories'=>$i18n['perm_manage_categories'],
		);
	}
		
	function show($view,$loc = null, $title = "") {
		global $db;
		
		$config = $db->selectObject('linkmodule_config',"location_data='".serialize($loc)."'");
      
		// Create the template
		$template = new template("linkmodule",$view,$loc);
		if(!$config) {
			$config->enable_categories = 0;
			$config->orderby = 'name';
			$config->orderhow = 0; // Ascending
			$config->open_in_a_new_window=1;
			$config->enable_rss = false;
			$config->enable_rss_categories = false;
		}
		if($config->open_in_a_new_window==1)
			$template->assign("target","_blank");
		else
			$template->assign("target","_self");

		switch ($config->orderhow) {
			// Four options, ascending, descending, by user selected rank, and random
			case 0:
				//usort($listings,'exponent_sorting_byNameAscending');
				$sortFunc = 'exponent_sorting_byNameAscending';
				break;
			case 1:
				//usort($listings,'exponent_sorting_byNameDescending');
				$sortFunc = 'exponent_sorting_byNameDescending';
				break;
			case 2:
				//sort the listings by their rank
				//usort($listings, 'exponent_sorting_byRankAscending');
				$sortFunc = 'exponent_sorting_byRankAscending';
				break;
			case 3:
				//shuffle($listings);
				$sortFunc = '';
				break;
		} 
		$template->assign('orderhow', $config->orderhow);
		
      // Get sorted categories
		$categories=$this->tools_getCategories($loc);
		$template->assign('categories', $categories);
      
      // Get sorted links and count them
		if ($config->enable_categories == true) {	  
			$data=$this->tools_getCategorizedLinks($loc,$categories,true,$sortFunc);
		} else {
			$data=$this->tools_getLinks($loc,$sortFunc);      
		}
		$cat_count=array();
		foreach ($categories as $id=>$category) {
			$cat_count[$id]=count($data[$id]);
		}
		$template->assign('enable_categories',$config->enable_categories);

      // Final assignations
		$template->assign('data',$data);
		$template->assign('cat_count',$cat_count);
		$template->register_permissions(
			array('administrate','configure','add','edit','delete','import','manage_categories'),
			$loc);
		$template->assign('moduletitle', $title);
      
      //If rss is enabled tell the view to show the RSS button	
		$template->assign('enable_rss', $config->enable_rss);
		$template->assign('enable_rss_categories', $config->enable_rss_categories AND $config->enable_rss);

		$template->output();
	}

    function getRSSContent($loc) {
		global $db;
      
		$config = $db->selectObject('linkmodule_config',"location_data='".serialize($loc)."'");
		if(!$config) {
			$config->rss_categories = true;
			$config->rss_add_category_name = true;
		} 
      
		$category_rss=NULL;
		if(isset($_GET['category'])) 
			if(is_numeric($_GET['category']))
				$category_rss=$_GET['category'];
         
		if($config->rss_categories OR $category_rss) {
			$categories=$this->tools_getCategories($loc);
			$categories_for_display_only=$categories;
			$data=$this->tools_getCategorizedLinks($loc,$categories);      
		} else  {
			$categories[0]="";
			$data=$this->tools_getLinks($loc);      
		}
         
		if($config->rss_add_category_name and !isset($categories_for_display_only)) 
			$categories_for_display_only=$this->tools_getCategories($loc);
      
		//Convert the links to rss items
		$rssitems = array();
		foreach ($categories as $id=>$category) {
			if($category_rss==NULL OR $id==$category_rss) {
				foreach($data[$id] as $item) {
					$rss_item = new FeedItem();
					$rss_item->title = $item->name;
					if($config->rss_add_category_name AND !$category_rss) {
						if($config->rss_categories) {
							if($category->name)
								$rss_item->title = $category->name." : ".$rss_item->title;
						} else {
						// note : item may have an invalid category (delected category)
							if($categories_for_display_only[$item->category_id]->name)
								$rss_item->title = $categories_for_display_only[$item->category_id]->name." : ".$rss_item->title;
						}
					}
					$rss_item->description = $item->description;
					$rss_item->link = $item->url;
					$rssitems[] = $rss_item;
				}
			}
		}
		return $rssitems;
	}
   
    // Tool function : get the list of categories (sorted)
   function tools_getCategories($loc) {
		global $db;
		$categories = $db->selectObjectsIndexedArray('category', "location_data='".serialize($loc)."'");
		if (!defined("SYS_SORTING")) require_once(BASE."subsystems/sorting.php");
		$c=null;$c->name = '';$c->id = 0;$c->rank = -1;
		$categories[0] = $c;
		uasort($categories, "exponent_sorting_byRankAscending");
		return($categories);
    }
      
   // Tool function : get the list of sorted links by categories
   // $purge=true : check if each links has a valid categories. Put it at top level otherwise
   function tools_getCategorizedLinks($loc,$categories,$purge=false,$sortFunc='exponent_sorting_byNameAscending') {
		global $db;
		// Prepare arrays
		$data = array();
		foreach($categories as $id=>$category) {
			$data[$id]=array();   // I did'nt find a better method to order $data[] like $categories[]
		}
		// Get links
		$links = $db->selectObjects("link","location_data='".serialize($loc)."' ");
		$cat_id = array_keys($categories);
		foreach($links as $link) {
			// If categories have been deleted
			if(!in_array($link->category_id,$cat_id)) {
				$link->category_id = 0;
				if($purge) {
					$db->updateObject($link,"link");
				}
			}
			$data[$link->category_id][]=$link;
		}
		// Sort links
		foreach ($categories as $id=>$category) {
//			usort($data[$id], "exponent_sorting_byNameAscending");
			usort($data[$id], $sortFunc);
		}
		return($data);
   }
   
   // Tool function : get the list of sorted links
   function tools_getLinks($loc,$sortFunc='exponent_sorting_byNameAscending') {
		global $db;
		$data = array();

		// Get and sort links
		$links = $db->selectObjects("link","location_data='".serialize($loc)."' ");
		usort($links, $sortFunc);
		$data[0]=$links;
//		usort($data[0], "exponent_sorting_byNameAscending");

		return($data);
   }
   
   function deleteIn($loc) {
		global $db;
		$db->delete('link',"location_data='".serialize($loc)."'");
		$db->delete('category', "location_data='".serialize($loc)."'");
		$db->delete('linkmodule_config', "location_data='".serialize($loc)."'");
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
		
		foreach ($db->selectObjects('link',"location_data='".serialize($oloc)."'") as $object) {
			unset($object->id);
			$object->location_data = serialize($nloc);
			if (isset($catid_map[$object->category_id])) {
				$object->category_id = $catid_map[$object->category_id];
			} else {
				$object->category_id = 0;
			}
			$db->insertObject($object,'link');
		}
		
		$conf = $db->selectObject('linkmodule_config', "location_data='".serialize($oloc)."'");
		unset($conf->id);
		$conf->location_data = serialize($nloc);
		$db->insertObject($conf,'linkmodule_config');
	}
	
	function spiderContent($item = null) {
		global $db;
		if (!defined('SYS_SEARCH')) require_once(BASE.'subsystems/search.php');
		$search = null;
		$search->category = exponent_lang_loadKey('modules/linkmodule/class.php','search_post_type');
		$search->ref_module = 'linkmodule';
		$search->ref_type = 'link';
		if ($item) {
			$db->delete('search',"ref_module='linkmodule' AND ref_type='link' AND original_id=" . $item->id);
			$search->original_id = $item->id;
			$search->title = ' ' . $item->name . ' ';
			$search->body = ' ' . $item->description . ' ';
			$search->location_data = $item->location_data;
			$db->insertObject($search,'search');
		}
      else {
			$db->delete('search',"ref_module='linkmodule' AND ref_type='link'");
			foreach ($db->selectObjects('link') as $item) {
				$search->original_id = $item->id;
				$search->title = ' ' . $item->name . ' ';
				$search->body = ' ' . $item->description . ' ';
				$search->location_data = $item->location_data;
				$db->insertObject($search,'search');
			}
		}
		
		return true;
	}
	
	function searchName() {
		return "Web Links";
	}
}

?>