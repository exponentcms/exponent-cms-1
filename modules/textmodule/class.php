<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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
# $Id$
##################################################

class textmodule {
	function name()		{ return "Text Module"; }
	function author()		{ return "James Hunt"; }
	function description()	{ return "Manages text."; }

	function hasContent() { return true; }
	function hasSources() { return true; }
	function hasViews()   { return true; }
	
	function supportsWorkflow() { return true; }

	function deleteIn($loc) {
		global $user, $db;
		$text = $db->selectObject("textitem","location_data='".serialize($loc)."'");
		if ($text) {
			$db->delete("textitem","location_data='".serialize($loc)."'");
			$db->delete("textitem_wf_revision","wf_original=".$text->id);
			$db->delete("textitem_wf_info","real_id=".$text->id);
			
			// Remove search key
			$db->delete("search","ref_module='textmodule' AND ref_type='textitem' AND original_id=" . $text->id);
		}
	}
	
	function copyContent($oloc,$nloc) {
		global $db;
		$text = $db->selectObject("textitem","location_data='".serialize($oloc)."'");
		if ($text) {
			unset($text->id);
			$text->location_data = serialize($nloc);
			$db->insertObject($text,"textitem");
		}
	}

	function permissions($internal = "") {
		pathos_lang_loadDictionary('modules','textmodule');
		return array(
			"administrate"=>TR_TEXTMODULE_PERM_ADMIN,
		//	"configure"=>"Configure",
			"edit"=>TR_TEXTMODULE_PERM_EDIT,
			"approve"=>TR_TEXTMODULE_PERM_APPROVE,
			"manage_approval"=>TR_TEXTMODULE_PERM_MANAGEAP
		);
	}

	function show($view,$loc,$title = "") {
		$template = new template("textmodule",$view,$loc);
		global $db;
		$textitem = $db->selectObject("textitem","location_data='" . serialize($loc) . "'");
		if (!$textitem) {
			$textitem->id = 0;
			$textitem->approved = 1;
			$textitem->text = "";
		}
		$template->assign("textitem",$textitem);
		$template->assign("moduletitle",$title);
		
		$template->register_permissions(array("administrate"/*,"configure"*/,"edit","approve","manage_approval"),$loc);
		$template->output($view);
	}
	
	function spiderContent($item=null) {
		global $db;
		
		pathos_lang_loadDictionary('modules','textmodule');
		
		if (!defined('SYS_SEARCH')) include_once(BASE.'subsystems/search.php');
		
		$search = null;
		$search->title = '';
		$search->view_link = '';
		$search->category = TR_TEXTMODULE_SEARCHTYPE;
		$search->ref_module = 'textmodule';
		$search->ref_type = 'textitem';
		
		if ($item) {
			$db->delete("search","ref_module='textmodule' AND ref_type='textitem' AND original_id=" . $item->id);
			$search->original_id = $item->id;
			$search->body = " " . pathos_search_removeHTML($item->text) . " ";
			$search->location_data = $item->location_data;
			$db->insertObject($search,"search");
		} else {
			$db->delete("search","ref_module='textmodule' AND ref_type='textitem'");
			foreach ($db->selectObjects("textitem") as $item) {
				$search->original_id = $item->id;
				$search->body = " " . pathos_search_removeHTML($item->text) . " ";
				$search->location_data = $item->location_data;
				$db->insertObject($search,"search");
			}
		}
	}
}

?>