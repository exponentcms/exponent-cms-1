<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Written and Designed by Adam Kessler
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

class youtubemodule {
	function name()		{ return 'YouTube Video Module'; }
	function author()	{ return 'OIC Group, Inc'; }
	function description()	{ return 'This Module allows you to embed YouTube Video into you site\'s pages'; }

	function hasContent() { return true; }
	function hasSources() { return true; }
	function hasViews()   { return true; }

	function supportsWorkflow() { return false; }

	function deleteIn($loc) {
		global $user, $db;
		$youtube = $db->selectObject('youtube',"location_data='".serialize($loc)."'");
		if ($youtube) {
			//eDebug($text); exit();
			$db->delete('youtube',"id=".$youtube->id);

			// Remove search key
			$db->delete('search',"ref_module='youtubemodule' AND ref_type='youtubevideo' AND original_id=" . $youtube->id);
		}
	}

	function copyContent($oloc,$nloc) {
	}

	function permissions($internal = '') {
		$i18n = exponent_lang_loadFile('modules/textmodule/class.php');
		return array(
			'administrate'=>$i18n['perm_administrate'],
			'edit'=>$i18n['perm_edit']
			//'approve'=>$i18n['perm_approve'],
			//'manage_approval'=>$i18n['perm_manage_approval']
		);
	}

	function show($view,$loc,$title = '') {
		global $db;
		$template = new template('youtubemodule',$view,$loc);
		$videos = $db->selectObjects('youtube',"location_data='".serialize($loc)."'");
		for ($i = 0; $i < count($videos); $i++) {
			$spliturl = explode('v=',$videos[$i]->url);
			$newurl = 'http://www.youtube.com/v/'.$spliturl[1];
			$videos[$i]->url = $newurl;
		}

		$template->assign('videos',$videos);
		$template->assign('moduletitle',$title);

		$template->register_permissions(array('administrate','edit'),$loc);
		$template->output($view);
	}

	function spiderContent($item=null) {
		global $db;

		if (!defined('SYS_SEARCH')) include_once(BASE.'subsystems/search.php');

		$search = null;
		$search->title = '';
		$search->view_link = '';
		$search->category = 'Video';
		$search->ref_module = 'youtubemodule';
		$search->ref_type = 'youtubevideo';

		if ($item) {
			$db->delete('search',"ref_module='youtubemodule' AND ref_type='youtubevideo' AND original_id=" . $item->id);
			$search->original_id = $item->id;
			$search->body = ' ' . exponent_search_removeHTML($item->description) . ' ';
			$search->location_data = $item->location_data;
			$db->insertObject($search,'search');
		} else {
			$db->delete('search',"ref_module='youtubemodule' AND ref_type='youtubevideo'");
			foreach ($db->selectObjects('youtube') as $item) {
				$search->original_id = $item->id;
				$search->body = ' ' . exponent_search_removeHTML($item->description) . ' ';
				$search->location_data = $item->location_data;
				$db->insertObject($search,'search');
			}
		}

		return true;
	}

	function searchName() {
		return "Videos";
	}
}

?>
