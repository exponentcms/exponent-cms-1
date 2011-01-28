<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
#
# Copyright (c) 2007 ACYSOS S.L. by Ignacio Ibeas
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
# $Id: view.php,v 1.5 2005/02/24 20:14:14 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

exponent_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_ACTION);

if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
if (!defined('SYS_FILES')) require_once(BASE.'subsystems/files.php');
if (!defined('SYS_PAGING')) { define('SYS_PAGING','paging'); require (BASE."subsystems/pagingObject.php");}

$view = isset($_GET['view']) ? $_GET['view'] : 'Default';		
$template = new template('listingmodule', $view, $loc);
//$viewconfig = $template->viewparams;

$config = $db->selectObject('listingmodule_config',"location_data='".serialize($loc)."'");
$title = $db->selectValue('container', 'title', "internal='".serialize($loc)."'");

if ($config) {
	if ($config == null) {
		$config->orderby = 'name';
		$config->orderhow = 0; // Ascending
		$itemsperpage = 10;
		//$loc = 0;
	} else {
		$itemsperpage = isset($config->items_perpage) ? $config->items_perpage : 10;
		//$loc = unserialize($config->location_data);
	}
	
	switch ($config->orderhow) {
		// Four options, alphabetical, ascending and descending, by user selected rank, and random
		case 0:
			//usort($listings,'exponent_sorting_byNameAscending');
			$orderby = ' ORDER BY name ASC ';
			$sortby = 'exponent_sorting_byNameAscending';
			break;
		case 1:
			//usort($listings,'exponent_sorting_byNameDescending');
			$orderby = ' ORDER BY name DESC ';
			$sortby = 'exponent_sorting_byNameDescending';
			break;
		case 2:
		//sort the listings by their rank
			//usort($listings, 'exponent_sorting_byRankAscending');
			$orderby = ' ORDER BY rank ASC ';
			$sortby = 'exponent_sorting_byRankAscending';
		break;
		case 3:
			//shuffle($listings);
			$orderby = '';
			$sortby = '';
			break;
	}

	// Calculate pages and get page count
	$itemcount = $db->countObjects('listing', "location_data='".serialize($loc)."'");
	$listingPaging = new PagingObject(isset($_GET['page']) ? (int) $_GET['page'] : 1, $itemcount, $itemsperpage);
//		$pageid = isset($_GET['page']) ? (int) $_GET['page'] : 1;
//		$pageid--;
	$pageid = $listingPaging->GetCurrentPage() - 1;	
	
	$where = "location_data='".serialize($loc)."'";
	$where .= $orderby;
//	$where .= " LIMIT " . $listingPaging->GetOffSet();

	// Get all of the categories for this Listing module:
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
			//Get all the questions & answers for this listing module. ($qnas stands for "Questions 'n Answers")
			$tmp = $db->selectObjects("listing","location_data='".serialize($loc)."' AND category_id=".$id);
			$catids = array_keys($cats); // for in_array check only
			for ($i = 0; $i < count($tmp); $i++) {
				if (!in_array($tmp[$i]->category_id,$catids)) {
//						echo 'Setting cat id to 0<br />';
					$tmp[$i]->category_id = 0;
				}
				if ($tmp[$i]->file_id == 0) {
					$tmp[$i]->picpath = '';
				} else {
					$file = $db->selectObject('file', 'id='.$tmp[$i]->file_id);
					$tmp[$i]->picpath = $file->directory.'/'.$file->filename;
				}					
			}
			usort($tmp, $sortby);
//			if ($config->orderhow == 3) { shuffle($tmp);}				
			$data[$id] = $tmp;
		}
	} else {
		$tmp = $db->selectObjects("listing",$where);
		$catids = array_keys($cats); // for in_array check only
		for ($i = 0; $i < count($tmp); $i++) {
			if (!in_array($tmp[$i]->category_id,$catids)) {
//					echo 'Setting cat id to 0<br />';
				$tmp[$i]->category_id = 0;
			}
			if ($tmp[$i]->file_id == 0) {
				$tmp[$i]->picpath = '';
			} else {
				$file = $db->selectObject('file', 'id='.$tmp[$i]->file_id);
				$tmp[$i]->picpath = $file->directory.'/'.$file->filename;
			}					
		}		
		usort($tmp, $sortby);
		if ($config->orderhow == 3) { shuffle($tmp);}				
		$data[0] = $tmp;
	}		

	$last = ($config->items_perpage * $listingPaging->GetCurrentPage());
	if (!$last) { $last = 9999; }
	$template->register_permissions(array('administrate','manage','configure','edit','delete','order','approve'),$loc);
	// Assign page data
	$template->assign("curpage", $listingPaging->GetCurrentPage());
	$template->assign("pagecount",$listingPaging->GetPageCount());
	$template->assign("uplimit", $listingPaging->GetUpperLimit());
	$template->assign("downlimit", $listingPaging->GetLowerLimit());

	$template->assign("first", ($config->items_perpage * $pageid));
	$template->assign("last", $last);
	
	$template->assign('listings', $listings);
	$template->assign('data',$data);		
	$template->assign('moduletitle', $title);
	$template->assign('config', $config);		
	$template->assign('list', $config->id);
	$template->output();
} else {
	echo SITE_404_HTML;
}

?>