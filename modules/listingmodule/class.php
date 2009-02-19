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
# $Id: class.php,v 1.8 2005/07/01 05:19:56 filetreefrog Exp $
##################################################

class listingmodule {
	function name() { return 'Listing Module'; }
	function description() { return 'A module for creating listings.  For example you could use this module to create personal bio pages for employees, or house listings for a realator'; }
	function author() { return 'Adam Kessler'; }

	function hasSources() { return true; }
	function hasContent() { return true; }
	function hasViews() { return true; }

	function supportsWorkflow() { return false; }

	function permissions($internal = '') {
		$i18n = exponent_lang_loadFile('modules/listingmodule/class.php');
		return array(
			'administrate'=>$i18n['perm_administrate'],
			'manage'=>$i18n['perm_manage_listing'],
			'configure'=>$i18n['perm_configure_listing'],
			'edit'=>$i18n['perm_edit_listing'],
			'delete'=>$i18n['perm_delete_listing'],
			'order'=>$i18n['perm_order_listing'],
		);
	}

	function show($view,$loc = null, $title = '') {
		global $db;

		$template = new template('listingmodule',$view,$loc);

		if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
		if (!defined('SYS_FILES')) require_once(BASE.'subsystems/files.php');
		if (!defined('SYS_PAGING')) { define('SYS_PAGING','paging'); require (BASE."subsystems/pagingObject.php");}

		$config = $db->selectObject('listingmodule_config',"location_data='".serialize($loc)."'");
		if ($config == null) {
			$config->orderby = 'name';
			$config->orderhow = 0; // Ascending
         	$itemsperpage = 10;
		} else {
		 $itemsperpage = isset($config->items_perpage) ? $config->items_perpage : 10;
		}

       switch ($config->orderhow) {
			// Four options, alphabetical, ascending and descending, by user selected rank, and random
			case 0:
				//usort($listings,'exponent_sorting_byNameAscending');
				$orderby = ' ORDER BY name ASC ';
				break;
			case 1:
				//usort($listings,'exponent_sorting_byNameDescending');
				$orderby = ' ORDER BY name DESC ';
				break;
			case 2:
            //sort the listings by their rank
            //usort($listings, 'exponent_sorting_byRankAscending');
				$orderby = ' ORDER BY rank ASC ';
            break;
			case 3:
				//shuffle($listings);
				$orderby = '';
				break;
		}

		// Calculate pages and get page count
		$itemcount = $db->countObjects('listing', "location_data='".serialize($loc)."'");
		$listingPaging = new PagingObject(
		  isset($_GET['page']) ? (int) $_GET['page'] : 1,
        $itemcount,$itemsperpage
		);

		$pageid = isset($_GET['page']) ? (int) $_GET['page'] : 1;
		$pageid--;
      $where = "location_data='".serialize($loc)."'";
      $where .= $orderby;
      $where .= " LIMIT " . $listingPaging->GetOffSet();

		// Get listings
		$listings = $db->selectObjects('listing',$where);
		if ($config->orderhow == 3) { shuffle($listings);}

		$directory = 'files/listingmodule/' . $loc->src;
		if (!file_exists(BASE.$directory)) {
			$err = exponent_files_makeDirectory($directory);
			if ($err != SYS_FILES_SUCCESS) {
				$template->assign('noupload',1);
				$template->assign('uploadError',$err);
			}
		}

		for($i=0; $i<count($listings); $i++) {
			if ($listings[$i]->file_id == 0) {
				$listings[$i]->picpath = '';
			} else {
				$file = $db->selectObject('file', 'id='.$listings[$i]->file_id);
				$listings[$i]->picpath = $file->directory.'/'.$file->filename;
			}
		}

		//sort the listings by their rank
		//usort($listings, 'exponent_sorting_byRankAscending');

		$template->register_permissions(array('administrate','manage','configure','edit','delete','order'),$loc);
        // Assign page data
        $template->assign("curpage", $listingPaging->GetCurrentPage());
        $template->assign("pagecount",$listingPaging->GetPageCount());
        $template->assign("uplimit", $listingPaging->GetUpperLimit());
        $template->assign("downlimit", $listingPaging->GetLowerLimit());

		$template->assign('listings', $listings);
		$template->assign('moduletitle', $title);
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
		return 'Listed Elements';
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
