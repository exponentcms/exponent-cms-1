<?php

##################################################
# __XYZZY_BOILER
# $Id$
##################################################
//GREP:HARDCODEDTEXT
class bannermodule {
	function name() { return "Banner Manager"; }
	function author() { return "James Hunt"; }
	function description() { return "Manages advertisements and click throughs."; }
	
	function hasSources() { return true; }
	function hasContent() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions() {
		return array(
			"administrate"=>"Administrate",
			"configure"=>"Configure",
			"manage"=>"Manage Banners",
			"manage_af"=>"Manage Affiliates"
		);
	}
	
	function deleteIn($loc) {
		global $db;
		$banners = $db->selectObjects("banner_ad","location_data='".serialize($loc)."'");
		foreach ($banners as $b) {
			$db->delete("banner_click","ad_id=".$b->id);
			$file = $db->selectObject("file","id=".$b->file_id);
			file::delete($file);
		}
		rmdir(BASE."files/bannermodule/".$loc->src);
		$db->delete("banner_ad","location_data='".serialize($loc)."'");
	}
	
	function copyContent($oloc,$nloc) {
		if (!defined("SYS_FILES")) include_once(BASE."subsystems/files.php");
		$directory = "files/bannermodule/".$nloc->src;
		if (!file_exists(BASE.$directory)) {
			switch(pathos_files_makeDirectory($directory)) {
				case SYS_FILES_FOUNDFILE:
				case SYS_FILES_NOTWRITABLE:
					return;
			}
		}
		
		global $db;
		foreach ($db->selectObjects("banner_ad","location_data='".serialize($oloc)."'") as $banner) {
			$file = $db->selectObject("file","id=".$banner->file_id);
			
			copy($file->directory."/".$file->filename,$directory."/".$file->filename);
			$file->directory = $directory;
			unset($file->id);
			$file->id = $db->insertObject($file,"file");
			
			$banner->location_data = serialize($nloc);
			$banner->file_id = $file->id;
			unset($banner->id);
			$db->insertObject($banner,"banner_ad");
		}
	}
	
	function spiderContent($item = null) {
		// Do nothing, no content
	}
	function show($view,$loc, $title = "") {
		global $db;
		
		$template = new template("bannermodule",$view,$loc);
		$template->assign("title",$title);
		
		$viewconfig = array("type"=>"default","number"=>1);
		if (is_readable($template->viewdir."/$view.config")) $viewconfig = include($template->viewdir."/$view.config");
		if ($viewconfig['type'] == "affiliates") {
			$af = $db->selectObjects("banner_affiliate");
			for ($i = 0; $i < count($af); $i++) {
				$af[$i]->bannerCount = $db->countObjects("banner_ad","affiliate_id=".$af[$i]->id);
				$af[$i]->contact_info = str_replace("\n","<br />",$af[$i]->contact_info);
			}
			if (!defined("SYS_SORTING")) include_once(BASE."subsystems/sorting.php");
			usort($af,"pathos_sorting_byNameAscending");
			
			$template->assign("affiliates",$af);
		} else {
			if (!defined("SYS_FILES")) include_once(BASE."subsystems/files.php");
		
			$directory = "files/bannermodule/" . $loc->src;
			if (!file_exists(BASE.$directory)) {
				switch(pathos_files_makeDirectory($directory)) {
					case SYS_FILES_FOUNDFILE:
						echo "Found a file in the directory path.";
						return;
					case SYS_FILES_NOTWRITABLE:
						echo "Unable to create directory to store files in.";
						return;
				}
			}
			
			$all = $db->selectObjects("banner_ad","location_data='".serialize($loc)."'");
			
			if ($viewconfig['type'] == "allbanners") {
				$bfiles = $db->selectObjectsIndexedArray("file","directory='".$directory."'");
				
				$template->assign("affiliates",bannermodule::listAffiliates());
				$template->assign("files",$bfiles);
				$template->assign("banners",$all);
			} else {
				$num = $viewconfig['number'];
				shuffle($all);
				$banners = array_slice($all,0,$num);
				
				for ($i = 0; $i < count($banners); $i++) {
					$banners[$i]->file = $db->selectObject("file","id=".$banners[$i]->file_id);
				}
				$template->assign("banners",$banners);
			}
		}
		$template->register_permissions(
			array("administrate","manage","manage_af"),
			$loc);
		$template->output();
	}
	
	function listAffiliates() {
		global $db;
		$affiliates = array();
		foreach ($db->selectObjects("banner_affiliate") as $af) {
			$affiliates[$af->id] = $af->name;
		}
		uasort($affiliates,"strnatcmp");
		return $affiliates;
	}
}

?>