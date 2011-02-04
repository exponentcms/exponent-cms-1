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
# $Id: class.php,v 1.6 2005/04/26 03:00:25 filetreefrog Exp $
##################################################

class slideshowmodule {
	function name() { return "Slideshow Manager"; }
	function description() { return "A simple way to display a slideshow of graphics"; }
	function author() { return ""; }
	
	function hasSources() { return true; }
	function hasContent() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = "") {
		if ($internal == "") {
			return array(
				"administrate"=>"Administrate",
				"configure"=>"Configure",
				"create_slide"=>"Create Slides",
				"delete_slide"=>"Delete Slides",
				"edit_slide"=>"Edit Slides"
			);
		} else {
			return array(
				"administrate"=>"Administrate",
				"configure"=>"Configure",
			);
		}
	}
	
	function show($view,$loc = null, $title = "") {
		if (!defined("SYS_FILES")) require_once(BASE."subsystems/files.php");
		
		$directory = "files/slideshowmodule/".$loc->src;
		if (!file_exists(BASE.$directory)) {
			switch(exponent_files_makeDirectory($directory)) {
				case SYS_FILES_FOUNDFILE:
					echo "Found a file in the directory path.";
					return;
				case SYS_FILES_NOTWRITABLE:
					echo "Unable to create directory to store files in.";
					return;
			}
		}
		
		global $db;
		
		$config = $db->selectObject("slideshowmodule_config","location_data='".serialize($loc)."'");
		if (!$config) {
			$config->delay = 1000;
			$config->random = 0;
		}
	
		$slides = array();
		$order = $config->random == 1 ? 'RAND()' : null;  // if this slideshow module is setup to be random then set the ORDERBY clause to rand()
		foreach ($db->selectObjects("slideshow_slide","location_data='".serialize($loc)."'", $order) as $s) {
			$s->file = $db->selectObject("file","id=".$s->file_id);
			$slides[] = $s;
		}
		
		$template = new template("slideshowmodule",$view,$loc);
		$template->assign("moduletitle",$title);
		$template->assign("number",count($slides));
		$template->assign("slides",$slides);
		$template->register_permissions(
			array("administrate","configure","create_slide","delete_slide","edit_slide"),
			$loc
		);
		$template->assign("unique","u".uniqid(""));
		$template->assign("config",$config);
		$template->output();
	}
	
	function deleteIn($loc) {
		global $db;
		$slides = $db->selectObjects("slideshow_slide","location_data='".serialize($loc)."'");
		foreach ($slides as $slide) {
			$file = $db->selectObject("file","id=".$slide->file_id);
			file::delete($file);
			$db->delete('file','id='.$file->id);
		}
		rmdir(BASE."files/slideshowmodule/".$loc->src);
		$db->delete("slideshowmodule_config","location_data='".serialize($loc)."'");
		$db->delete("slideshow_slide","location_data='".serialize($loc)."'");
	}
	
	function copyContent($oloc,$nloc) {
		global $db;
		$directory = "files/slideshowmodule/".$nloc->src;
		$nloc = serialize($nloc);
		
		if (!defined("SYS_FILES")) require_once(BASE."subsystems/files.php");
		
		if (!file_exists(BASE.$directory)) {
			exponent_files_makeDirectory($directory);
			if (!file_exists(BASE.$directory)) {
				return;
			}
		}
		
		foreach ($db->selectObjects("slideshow_slide","location_data='".serialize($oloc)."'") as $slide) {
			$file = $db->selectObject("file","id=".$slide->file_id);
			copy(BASE.$file->directory.'/'.$file->filename,BASE.$directory.'/'.$file->filename);
			if (file_exists(BASE.$directory.'/'.$file->filename)) {
				$file->directory = $directory;
				unset($file->id);
				$slide->file_id = $db->insertObject($file,'file');
				$slide->location_data = $nloc;
				$db->insertObject($slide,'slideshow_slide');
			}
		}
		
	}
	
	function spiderContent($item = null) {
		// No content searchable.
		return false;
	}
}

?>
