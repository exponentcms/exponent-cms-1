<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
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

class snippetmodule {
	function name()		{ return 'Code Snippet'; }
	function author()		{ return 'Ron Miller, OIC Group, Inc'; }
	function description()	{ return 'Text module without the rich text editor -- Perfect for inserting HTML code snippets.'; }

	function hasContent() { return true; }
	function hasSources() { return true; }
	function hasViews()   { return true; }
	
	function supportsWorkflow() { return true; }

	function deleteIn($loc) {
		global $user, $db;
		$text = $db->selectObject('textitem',"location_data='".serialize($loc)."'");
		if ($text) {
			//eDebug($text); exit();
			$db->delete('textitem',"id=".$text->id);
			$db->delete('textitem_wf_revision','wf_original='.$text->id);
			$db->delete('textitem_wf_info','real_id='.$text->id);
			
			// Remove search key
			$db->delete('search',"ref_module='textmodule' AND ref_type='textitem' AND original_id=" . $text->id);
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

	function permissions($internal = '') {
		$i18n = exponent_lang_loadFile('modules/textmodule/class.php');
		return array(
			'administrate'=>$i18n['perm_administrate'],
			'edit'=>$i18n['perm_edit'],
			'approve'=>$i18n['perm_approve'],
			'manage_approval'=>$i18n['perm_manage_approval']
		);
	}

	function show($view,$loc,$title = '') {
		global $db;
		
		$template = new template('snippetmodule',$view,$loc);
		$location = serialize($loc);
		$cache = exponent_sessions_getCacheValue('textmodule');
		if (!isset($cache[$location])){
			$textitem = $db->selectObject('textitem',"location_data='" . $location . "'");
			if (!$textitem) {
				$textitem->id = 0;
				$textitem->approved = 1;
				$textitem->text = '';
			}
			$cache[$location] = $textitem;
			exponent_sessions_setCacheValue('textmodule', $cache);
		} else {
			$textitem = $cache[$location];
		}
		
		$highlight = highlight_string($textitem->text, true); 
		
		ob_start();
		$tempfile = 'tempfile.php';
		unlink($tempfile); 
		$out = "<?php ".$textitem->text." ?>";
		file_put_contents($tempfile,$out); 
//		include($tempfile);
		$output = ob_get_clean();
//		unlink($tempfile); 

		$template->assign('textitem',$textitem);
		$template->assign('highlight',$highlight);
		$template->assign('output',$output);
		$template->assign('moduletitle',$title);
		
		$template->register_permissions(array('administrate','edit','approve','manage_approval'),$loc);
		unset($textitem);	
		$template->output($view);
	}
	
	function spiderContent($item=null) {
// done in navigation module	
		/*	
		global $db;
		
		if (!defined('SYS_SEARCH')) include_once(BASE.'subsystems/search.php');
		
		$search = null;
		$search->title = '';
		$search->view_link = '';
		$search->category = exponent_lang_loadKey('modules/textmodule/class.php','search_post_type');
		$search->ref_module = 'textmodule';
		$search->ref_type = 'textitem';
		
		if ($item) {
			$db->delete('search',"ref_module='textmodule' AND ref_type='textitem' AND original_id=" . $item->id);
			$search->original_id = $item->id;
			$search->body = ' ' . exponent_search_removeHTML($item->text) . ' ';
			$search->location_data = $item->location_data;
			$db->insertObject($search,'search');
		} else {
			$db->delete('search',"ref_module='textmodule' AND ref_type='textitem'");
			foreach ($db->selectObjects('textitem') as $item) {
				$search->original_id = $item->id;
				$search->body = ' ' . exponent_search_removeHTML($item->text) . ' ';
				$search->location_data = $item->location_data;
				$db->insertObject($search,'search');
			}
		}

		*/		
		return true;  // provide comfort factor for spider site
	}
	
	function searchName() {
//		return "Other web pages";
	}
}

?>
