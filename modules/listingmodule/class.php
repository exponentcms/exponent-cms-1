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

class listingmodule {
	function name() { return 'Listing'; }
	function description() { return 'Manages a set of listings.  For example you could use this to create personal bio pages for employees, or house listings for a realator'; }
	function author() { return 'Adam Kessler'; }

	function hasSources() { return true; }
	function hasContent() { return true; }
	function hasViews() { return true; }

	function supportsWorkflow() { return true; }

	function permissions($internal = '') {
		$i18n = exponent_lang_loadFile('modules/listingmodule/class.php');
		return array(
			'administrate'=>$i18n['perm_administrate'],
			'manage'=>$i18n['perm_manage_listing'],
			'configure'=>$i18n['perm_configure_listing'],
			'edit'=>$i18n['perm_edit_listing'],
			'delete'=>$i18n['perm_delete_listing'],
			'order'=>$i18n['perm_order_listing'],
			'approve'=>$i18n['perm_manage_approval'],
		);
	}

	function show($view,$loc = null, $title = '') {
		global $db;

		$template = new template('listingmodule',$view,$loc);
//		$viewconfig = $template->viewparams;
		
		if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
		if (!defined('SYS_FILES')) require_once(BASE.'subsystems/files.php');
		if (!defined('SYS_PAGING')) { define('SYS_PAGING','paging'); require (BASE."subsystems/pagingObject.php");}

		$config = $db->selectObject('listingmodule_config',"location_data='".serialize($loc)."'");
		if ($config == null) {
			$config->enable_categories = 0;
			$config->orderby = 'name';
			$config->orderhow = 0; // Ascending
         	$itemsperpage = 10;
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
					foreach ($db->selectObjects('listing',"location_data='".serialize($loc)."' AND category_id=".$c->id) as $listing) {
						$listing->rank = $rank;
						$db->updateObject($listing,'listing');
						$rank++;
					}
				}
			} else {
				// Recaculate blindly, ignoring categories.
				$listings = $db->selectObjects('listing',"location_data='".serialize($loc)."'");
				usort($listings, 'exponent_sorting_byRankAscending');
				$rank = 0;
				foreach ($listings as $listing) {
					$listing->rank = $rank;
					$listing->category_id = 0;
					$db->updateObject($listing,'listing');
					$rank++;
				}
			}
			$config->recalc = 0;
			$db->updateObject($config,'listingmodule_config',"location_data='".serialize($loc)."'");
		}

		$itemsperpage = isset($config->items_perpage) ? $config->items_perpage : 10;
		
		switch ($config->orderhow) {
			// Four options, alphabetical, ascending and descending, by user selected rank, and random
			case 0:
//				$orderby = ' ORDER BY name ASC ';
				$sortby = 'exponent_sorting_byNameAscending';
				break;
			case 1:
//				$orderby = ' ORDER BY name DESC ';
				$sortby = 'exponent_sorting_byNameDescending';
				break;
			case 2:
            //sort the listings by their rank
//				$orderby = ' ORDER BY rank ASC ';
				$sortby = 'exponent_sorting_byRankAscending';
            break;
			case 3:
				//shuffle($listings);
//				$orderby = '';
				$sortby = '';
				break;
		}

		// Calculate pages and get page count
		$itemcount = $db->countObjects('listing', "location_data='".serialize($loc)."'");
		$listingPaging = new PagingObject(isset($_GET['page']) ? (int) $_GET['page'] : 1, $itemcount, $itemsperpage);
//		$pageid = isset($_GET['page']) ? (int) $_GET['page'] : 1;
//		$pageid--;
		$pageid = $listingPaging->GetCurrentPage() - 1;	
		
//		$where = "location_data='".serialize($loc)."'";
//		$where .= $orderby;
//		$where .= " LIMIT " . $listingPaging->GetOffSet();

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
//				if ($config->orderhow == 3) { shuffle($tmp);}				
				$data[$id] = $tmp;
			}
		} else {
//			$tmp = $db->selectObjects("listing",$where);
			$tmp = $db->selectObjects("listing","location_data='".serialize($loc)."'");
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
		
//		$template->assign('listings', $listings);
		$template->assign('data',$data);		
		$template->assign('moduletitle', $title);
		$template->assign('config', $config);	
		$template->assign('list', $config->id);
		$template->output();
	}

	function deleteIn($loc) {
		global $db;
		// first delete any files associated with the listings
		foreach ($db->selectObjects('listing',"location_data='".serialize($loc)."'") as $listing) {
        if ($listing->file_id != '') {
          $file = $db->selectObject('file', 'id='.$listing->file_id);
          if (is_object($file)) {
	          file::delete($file);
	          $db->delete('file','id='.$file->id);
	      }
	    }
		  $db->delete('listing', 'id='.$listing->id);
      }
      // now remove the empty directory
		rmdir(BASE.'files/listingmodule/'.$loc->src);
	}

	function copyContent($oloc,$nloc) {
		global $db;

		if (!defined('SYS_FILES')) require_once(BASE.'subsystems/files.php');
		$directory = 'files/listingmodule/'.$nloc->src;
		if (!file_exists(BASE.$directory) && exponent_files_makeDirectory($directory) != SYS_FILES_SUCCESS) {
			return;
		}

		foreach ($db->selectObjects('listing',"location_data='".serialize($oloc)."'") as $l) {
		// Check for pictures in the listing

			if ($l->file_id != '') {
				$file = $db->selectObject('file','id='.$l->file_id);

				copy($file->directory.'/'.$file->filename,$directory.'/'.$file->filename);

				$file->directory = $directory;
				unset($file->id);
				$file->id = $db->insertObject($file,'file');
				$l->file_id = $file->id;
			}
			$l->location_data = serialize($nloc);
			unset($l->id);
			$db->insertObject($l,'listing');
		}
	}

	function searchName() {
		return 'Other Listings';
	}

	function spiderContent($item = null) {
		global $db;

		if (!defined('SYS_SEARCH')) require_once(BASE.'subsystems/search.php');

		$search = null;
		$search->category = exponent_lang_loadKey('modules/listingmodule/class.php','search_category');
		$search->ref_module = 'listingmodule';
		$search->ref_type = 'listing';

		if ($item) {
			$db->delete('search',"ref_module='listingmodule' AND ref_type='listing' AND original_id=" . $item->id);
			$search->original_id = $item->id;
			$search->title = ' ' . $item->name . ' ';
			$search->view_link = 'index.php?module=listingmodule&action=view_listing&id='.$item->id;
			$search->body = ' ' . exponent_search_removeHTML($item->body) . ' ';
			$search->location_data = $item->location_data;
			$db->insertObject($search,'search');
		} else {
			$db->delete('search',"ref_module='listingmodule' AND ref_type='listing'");
			foreach ($db->selectObjects('listing') as $item) {
				$search->original_id = $item->id;
				$search->title = ' ' . $item->name . ' ';
				$search->view_link = 'index.php?module=listingmodule&action=view_listing&id='.$item->id;
				$search->body = ' ' . exponent_search_removeHTML($item->body) . ' ';
				$search->location_data = $item->location_data;
				$db->insertObject($search,'search');
			}
		}

		return true;
	}
}

?>
