<?php

##################################################
#
# Copyright (c) 2004-2011 OIC Group, Inc.
# Written and Designed by James Hunt
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################

class resourcesmodule {
	function name() { return exponent_lang_loadKey('modules/resourcesmodule/class.php','module_name'); }
	function author() { return 'OIC Group, Inc'; }
	function description() { return exponent_lang_loadKey('modules/resourcesmodule/class.php','module_description'); }
	
	function hasContent() { return true; }
	function hasViews() { return true; }
	function hasSources() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = '') {
		$i18n = exponent_lang_loadFile('modules/resourcesmodule/class.php');
		if ($internal == '') {
			return array(
				'administrate'=>$i18n['perm_administrate'],
				'post'=>$i18n['perm_post'],
				'edit'=>$i18n['perm_edit'],
				'delete'=>$i18n['perm_delete'],
				'manage_approval'=>$i18n['perm_manage_approval'],
				'can_download'=>'Can Download',
			);
		} else {
			return array(
				'administrate'=>$i18n['perm_administrate'],
				'edit'=>$i18n['perm_editone'],
				'delete'=>$i18n['perm_deleteone'],
				'manage_approval'=>$i18n['perm_manage_approval'],
				'can_download'=>'Can Download',
			);
		}
	}
	
	function getLocationHierarchy($loc) {
		if ($loc->int == '') {
			return array($loc);
		} else {
			return array($loc,exponent_core_makeLocation($loc->mod,$loc->src));
		}
	}
	
	function show($view,$loc,$title = '') {
		if (!defined('SYS_FILES')) require_once(BASE.'subsystems/files.php');
		if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
		if (!defined('SYS_USERS')) include_once(BASE.'subsystems/users.php');
		include(BASE.'external/mp3file.php');
		
		$template = new template('resourcesmodule',$view,$loc);
		
		$directory = 'files/resourcesmodule/' . $loc->src;
		if (!file_exists(BASE.$directory)) {
			$err = exponent_files_makeDirectory($directory);
			if ($err != SYS_FILES_SUCCESS) {
				$template->assign('noupload',1);
				$template->assign('uploadError',$err);
			}
		}
		
		global $db;
		global $user;
		$location = serialize($loc);	
//+		
		// $cache = exponent_sessions_getCacheValue('resourcesmodule');
		
        // if(!isset($cache[$location])){
			// $resources = $db->selectObjects('resourceitem',"location_data='".$location."'");			
			// $cache[$location] = $resources;
			// exponent_sessions_setCacheValue('resourcesmodule', $cache);
		// } else {
			// $resources = $cache[$location];
		// }		
//-				
		$iloc = exponent_core_makeLocation($loc->mod,$loc->src);
				
		$viewparams = $template->viewparams;
		if ($viewparams == null) {
			$viewparams = array('type'=>"default");
		}
		
		$config = $db->selectObject('resourcesmodule_config',"location_data='".serialize($loc)."'");
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
					foreach ($db->selectObjects('resourceitem',"location_data='".serialize($loc)."' AND category_id=".$c->id) as $resource) {
						$resource->rank = $rank;
						$db->updateObject($resource,'resourceitem');
						$rank++;
					}
				}
			} else {
				// Recaculate blindly, ignoring categories.
				$resources = $db->selectObjects('resourceitem',"location_data='".serialize($loc)."'");
				usort($resources, 'exponent_sorting_byRankAscending');
				$rank = 0;
				foreach ($resources as $resource) {
					$resource->rank = $rank;
					$resource->category_id = 0;
					$db->updateObject($resource,'resourceitem');
					$rank++;
				}
			}
			$config->recalc = 0;
			$db->updateObject($config,'resourcemodule_config',"location_data='".serialize($loc)."'");
		} 

		// Get all of the categories for this Resources module:
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

		switch($config->orderby) {
        	case "posted":
				$field = "Posted";
                break;
			case "edited":
				$field = "Edited";
                break;
			case "downloads":
				$field = "Downloads";
                break;
			case "name":
				$field = "Name";
                break;
			default:
				$field = "Posted";
                break;
        }
        
		switch ($config->orderhow) {
			// Four options, ascending, descending, by user selected rank, and random
			case 0:
				//usort($listings,'exponent_sorting_byNameAscending');
				$sortFunc = 'exponent_sorting_by'.$field.'Ascending';
				break;
			case 1:
				//usort($listings,'exponent_sorting_byNameDescending');
				$sortFunc = 'exponent_sorting_by'.$field.'Descending';
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

		$data = array();
		$cat_count = array();
		$resource_count = 0;
		if ($config->enable_categories == false || $viewparams['type'] == 'recent') {
			$tmp = $db->selectObjects("resourceitem","location_data='".serialize($loc)."'");
			$catids = array_keys($cats); // for in_array check only
			for ($i = 0; $i < count($tmp); $i++) {
				if (!in_array($tmp[$i]->category_id,$catids)) {
					$tmp[$i]->category_id = 0;
				}
				if ($tmp[$i]->file_id == 0) {
					$tmp[$i]->mimetype = '';
					$tmp[$i]->filename = '';
					$tmp[$i]->filesize = 0;
					$tmp[$i]->duration = '0:0';
				} else {
					$file = $db->selectObject('file', 'id='.$tmp[$i]->file_id);
					$tmp[$i]->mimetype = $db->selectObject('mimetype',"mimetype='".$file->mimetype."'");
//					$tmp[$i]->filename = URL_BASE.'/'.$file->directory.'/'.$file->filename;
					$tmp[$i]->filename = URL_FULL.$file->directory.'/'.$file->filename;
					$tmp[$i]->fileexists = file_exists(BASE.$file->directory.'/'.$file->filename);
					$tmp[$i]->filesize = resourcesmodule::formatBytes(filesize(BASE.$file->directory.'/'.$file->filename));
					if (($file->mimetype == "audio/mpeg") && ($tmp[$i]->fileexists)){
						$mp3 = new mp3file(BASE.$file->directory.'/'.$file->filename);
						$id3 = $mp3->get_metadata();
						if (($id3['Encoding']=='VBR') || ($id3['Encoding']=='CBR')) {
							$tmp[$i]->duration = $id3['Length mm:ss'];
						} 
					} else {
						$tmp[$i]->duration = 'Unknown';
					}
				}	
				$iloc->int = $tmp[$i]->id;
				$tmp[$i]->permissions = array(
					'administrate'=>exponent_permissions_check('administrate',$iloc),
					'edit'=>exponent_permissions_check('edit',$iloc),
					'delete'=>exponent_permissions_check('delete',$iloc),
					'manage_approval'=>exponent_permissions_check('manage_approval',$iloc),	
				);	
				if ($tmp[$i]->flock_owner != 0) {
					$tmp[$i]->lock_owner = exponent_users_getUserById($tmp[$i]->flock_owner);
					$tmp[$i]->locked = 1;
				} else {
					$tmp[$i]->locked = 0;
				}
				if ($tmp[$i]->edited == 0) {
					$tmp[$i]->edited = $tmp[$i]->posted;
				}				
			}	
			if ($viewparams['type'] == 'recent') {
				usort($tmp, 'exponent_sorting_byEditedDescending');
			} elseif ($config->orderhow == 3) {
				shuffle($tmp);
			} else {				
				usort($tmp, $sortFunc);
			}
			$data[0] = $tmp;
			$resource_count = count($tmp);
		} else {
			foreach ($cats as $id=>$c) {
				//Get all the questions & answers for this resources module. 
				$tmp = $db->selectObjects("resourceitem","location_data='".serialize($loc)."' AND category_id=".$id);
				$catids = array_keys($cats); // for in_array check only
				for ($i = 0; $i < count($tmp); $i++) {
					if (!in_array($tmp[$i]->category_id,$catids)) {
						$tmp[$i]->category_id = 0;
					}
					if ($tmp[$i]->file_id == 0) {
						$tmp[$i]->mimetype = '';
						$tmp[$i]->filename = '';
						$tmp[$i]->filesize = 0;
						$tmp[$i]->duration = '0:0';
					} else {
						$file = $db->selectObject('file', 'id='.$tmp[$i]->file_id);
						$tmp[$i]->mimetype = $db->selectObject('mimetype',"mimetype='".$file->mimetype."'");
//						$tmp[$i]->filename = URL_BASE.'/'.$file->directory.'/'.$file->filename;
						$tmp[$i]->filename = URL_FULL.$file->directory.'/'.$file->filename;
						$tmp[$i]->fileexists = file_exists(BASE.$file->directory.'/'.$file->filename);
						$tmp[$i]->filesize = resourcesmodule::formatBytes(filesize(BASE.$file->directory.'/'.$file->filename));
						if (($file->mimetype == "audio/mpeg") && ($tmp[$i]->fileexists)) {
							$mp3 = new mp3file(BASE.$file->directory.'/'.$file->filename);
							$id3 = $mp3->get_metadata();
							if (($id3['Encoding']=='VBR') || ($id3['Encoding']=='CBR')) {
								$tmp[$i]->duration = $id3['Length mm:ss'];
							} 
						} else {
							$tmp[$i]->duration = 'Unknown';
						}
					}
					$iloc->int = $tmp[$i]->id;
					$tmp[$i]->permissions = array(
						'administrate'=>exponent_permissions_check('administrate',$iloc),
						'edit'=>exponent_permissions_check('edit',$iloc),
						'delete'=>exponent_permissions_check('delete',$iloc),
						'manage_approval'=>exponent_permissions_check('manage_approval',$iloc),	
					);		
					if ($tmp[$i]->flock_owner != 0) {
						$tmp[$i]->lock_owner = exponent_users_getUserById($tmp[$i]->flock_owner);
						$tmp[$i]->locked = 1;
					} else {
						$tmp[$i]->locked = 0;
					}	
					if ($tmp[$i]->edited == 0) {
						$tmp[$i]->edited = $tmp[$i]->posted;
					}					
				}
				usort($tmp, $sortFunc);
				$data[$id] = $tmp;
				$cat_count[$id] = count($tmp);
				$resource_count += count($tmp);
			}
		}		
//+		
		// for ($i = 0; $i < count($resources); $i++) {
			// $iloc->int = $resources[$i]->id;
			// $resources[$i]->permissions = array(
				// 'administrate'=>exponent_permissions_check('administrate',$iloc),
				// 'edit'=>exponent_permissions_check('edit',$iloc),
				// 'delete'=>exponent_permissions_check('delete',$iloc),
				// 'manage_approval'=>exponent_permissions_check('manage_approval',$iloc),				
			// );
		// }
		// if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
		// usort($resources,'exponent_sorting_byRankAscending');
		
		// $rfiles = array();
		// foreach ($db->selectObjects('file',"directory='$directory'") as $file) {
			// $file->mimetype = $db->selectObject('mimetype',"mimetype='".$file->mimetype."'");
			// $rfiles[$file->id] = $file;
		// }
//-		
		$template->assign('moduletitle',$title);
//		$template->assign('resources',$resources);
//		$template->assign('files',$rfiles);
		$template->assign('data',$data);	
		$template->assign('user',$user);		
		$template->assign('cat_count',$cat_count);	
		$template->assign('resource_count',$resource_count);			
		$template->assign('config',$config);
		$template->register_permissions(
			array('administrate','configure','post','edit','delete','manage_approval','can_download'),
			$loc);	
		$template->output($view);		
	}
	
	function deleteIn($loc) {
		global $db;
		$refcount = $db->selectValue('sectionref', 'refcount', "source='".$loc->src."'");
		if ($refcount <= 0) {
			foreach($db->selectObjects('resourceitem',"location_data='".serialize($loc)."'") as $res) {
				/*foreach ($db->selectObjects('resourceitem_wf_revision','wf_original='.$res->id) as $wf_res) {
					$file = $db->selectObject('file','id='.$wf_res->file_id);
					file::delete($file);
					$db->delete('file','id='.$file->id);
				}*/
				$db->delete('resourceitem_wf_revision','wf_original='.$res->id);
			}
			rmdir(BASE.'files/resourcesmodule/'.$loc->src);
			$db->delete('resourceitem',"location_data='".serialize($loc)."'");
		}
	}
	
	function copyContent($oloc,$nloc) {
		if (!defined('SYS_FILES')) require_once(BASE.'subsystems/files.php');
		$directory = 'files/resourcesmodule/'.$nloc->src;
		if (!file_exists(BASE.$directory) && exponent_files_makeDirectory($directory) != SYS_FILES_SUCCESS) {
			return;
		}
		
		global $db;
		foreach ($db->selectObjects('resourceitem',"location_data='".serialize($oloc)."'") as $r) {
			$file = $db->selectObject('file','id='.$r->file_id);
			
			copy($file->directory.'/'.$file->filename,$directory.'/'.$file->filename);
			$file->directory = $directory;
			unset($file->id);
			$file->id = $db->insertObject($file,'file');
			
			$r->location_data = serialize($nloc);
			$r->file_id = $file->id;
			unset($r->id);
			$db->insertObject($r,'resourceitem');
		}
	}

	function searchName() {
		return "File Downloads";
	}
	
	function spiderContent($item = null) {
		$i18n = exponent_lang_loadFile('modules/resourcesmodule/class.php');
		
		global $db;
		
		if (!defined('SYS_SEARCH')) require_once(BASE.'subsystems/search.php');
		
		$search = null;
		$search->category = $i18n['search_category'];
		$search->ref_module = 'resourcesmodule';
		$search->ref_type = 'resourceitem';
		
		if ($item) {
			$db->delete('search',"ref_module='resourcesmodule' AND ref_type='resourceitem' AND original_id=" . $item->id);
			$search->original_id = $item->id;
			$search->body = ' ' . exponent_search_removeHTML($item->description) . ' ';
			$search->title = ' ' . $item->name . ' ';
			$search->location_data = $item->location_data;
			$search->view_link = 'index.php?module=resourcesmodule&action=view&id='.$item->id;
			$db->insertObject($search,'search');
		} else {
			$db->delete('search',"ref_module='resourcesmodule' AND ref_type='resourceitem'");
			foreach ($db->selectObjects('resourceitem') as $item) {
				$search->original_id = $item->id;
				$search->body = ' ' . exponent_search_removeHTML($item->description) . ' ';
				$search->title = ' ' . $item->name . ' ';
				$search->location_data = $item->location_data;
				$search->view_link = 'index.php?module=resourcesmodule&action=view&id='.$item->id;
				$db->insertObject($search,'search');
			}
		}
		
		return true;
	}
	
	function getRSSContent($loc) {
		global $db;
	
		//Get this modules configuration data
		$config = $db->selectObject('resourcesmodule_config',"location_data='".serialize($loc)."'");
		if ($config->rss_limit > 0) {
			$rsslimit = $db->limit($config->rss_limit,0);
		} else {
			$rsslimit = "";
		}
		
		$cats = $db->selectObjectsIndexedArray("category");
		$cats[0] = null;
		$cats[0]->name = 'None';
		
		//Get this modules items
		$items = array();
		$items = $db->selectObjects("resourceitem", "location_data='".serialize($loc)."' AND approved='1'", 'posted DESC'.$rsslimit);
		
		//Convert the resource items to rss items
		$rssitems = array();
		foreach ($items as $key => $item) {	
			$file = $db->selectObject('file', 'id='.$item->file_id);
			$rss_item = new FeedItem();
			$rss_item->title = $item->name;
			$rss_item->description = $item->description;
			$rss_item->date = date('r',$item->posted);
			$rss_item->link = "http://".HOSTNAME.PATH_RELATIVE."index.php?module=resourcesmodule&action=download_resource&id=".$item->id;			
			//$rss_item->link = URL_FULL.$file->directory.'/'.$file->filename;
			$rss_item->syndicationURL = "http://".HOSTNAME.PATH_RELATIVE."/".$_SERVER['PHP_SELF'];
			$rss_item->guid = $rss_item->link;
/*
			$itunes = new iTunes();
			$itunes->summary = $rss_item->description;
			$itunes->author = SITE_TITLE;
			$itunes->owner_email = SMTP_FROMADDRESS;
			$rss_item->itunes = $itunes;
*/
			$rss_item->enclosure = new Enclosure();
		   	//$rss_item->enclosure->url = URL_FULL.'index.php?module=resourcesmodule&action=download_resource&id='.$item->id;
			$rss_item->enclosure->url = URL_FULL.$file->directory.'/'.$file->filename;
		   	$rss_item->enclosure->length = filesize(BASE.$file->directory.'/'.$file->filename);
			$rss_item->enclosure->type = $file->mimetype;
			if ($rss_item->enclosure->type == 'audio/mpeg') $rss_item->enclosure->type = 'audio/mpg';
			if ($config->enable_categories == 1) {
				$rss_item->category = array($cats[$item->category_id]->name);
			}
			// Add the item to the array.
			$rssitems[$key] = $rss_item;
		}
		return $rssitems;
	}
	
	function formatBytes($bytes, $precision = 2) { 
		$units = array('B', 'KB', 'MB', 'GB', 'TB'); 
		$bytes = max($bytes, 0); 
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
		$pow = min($pow, count($units) - 1); 
		$bytes /= pow(1024, $pow); 
		return round($bytes, $precision) . ' ' . $units[$pow]; 
	} 

}

?>
