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
# $Id: class.php,v 1.10.2.2 2005/04/28 14:26:40 filetreefrog Exp $
##################################################

class rssmodule {
	function name() { return "Module syndication RSS"; }
	function author() { return "croby / geronimo / with MagpieRSS"; }
	function description() { return "manage rss syndication"; }
	
	function hasContent() { return false; }
	function hasSources() { return true; }
	function hasViews()   { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = '') {
		$i18n = exponent_lang_loadFile('modules/rssmodule/class.php');
		return array(
			'administrate'=>$i18n['perm_administrate'],
			'edit'=>$i18n['perm_edit'],
			'approve'=>$i18n['perm_approve'],
			'manage_approval'=>$i18n['perm_manage_approval']
		);
	}
	
	function getLocationHierarchy($loc) {
		if ($loc->int == '') return array($loc);
		else return array($loc,exponent_core_makeLocation($loc->mod,$loc->src));
	}
	
	
	
	function show($view,$loc = null,$title = "") {
		global $db;
		
		$config = $db->selectObject("rssmodule_config","location_data='".serialize($loc)."'");
		if ($config == null) {
			$config->url='';
			$config->item_limit = 10;
			$config->age=60;
		}
		if (!defined('MAGPIE_DIR'))
			define('MAGPIE_DIR', BASE.'/external/magpierss/');
		//echo MAGPIE_DIR;
		require_once(MAGPIE_DIR.'rss_fetch.inc');
		require_once(MAGPIE_DIR.'rss_utils.inc');
		if (!defined('MAGPIE_DEBUG'))
		 define('MAGPIE_DEBUG', 1);
		if (!defined('MAGPIE_CACHE_DIR'))
		 define('MAGPIE_CACHE_DIR',BASE.'tmp/cache');
		if (!defined('MAGPIE_CACHE_AGE'))
		 define('MAGPIE_CACHE_AGE', $config->age);
		// define('MAGPIE_CACHE_ON', 0)

		// Check permissions for AP link
				
	
		$template = new template('rssmodule',$view,$loc);
		$template->assign('moduletitle',$title);
		$template->register_permissions(
			array('administrate','configure'),
			$loc
		);
		//$template->register_permissions(array('administrate','configure','add_item','delete_item','edit_items','manage_approval','view_unpublished'),$loc);

		if ( $config->url ) 
		{
		// assign a variable to smarty for use in the template
		$template->assign('rss_url', $config->url);
	 
		// use MagpieRSS to fetch remote RSS file, and parse it
		$rss = fetch_rss( $config->url );

		$max = $db->selectObject("rssmodule_config", "item_limit");
		
		$max = $max->item_limit;
		
		$count=0;
		foreach ($rss->items as $item) {
			$count++;
			if ($count>$max) {
				array_pop($rss->items);
			}
			
				
		}
		
		// if fetch_rss returned false, we encountered an error
			if ( !$rss ) {
			$template->assign( 'error', "erreur" );
			}
		$template->assign('rss', $rss );
		$item = $rss->items[0];
		if (isset( $item['dc']['date'])) $date = parse_w3cdtf( $item['dc']['date'] );
		else $date = parse_w3cdtf( $item['date_timestamp'] );	
		$template->assign( 'date', $date );
		}
		$template->register_permissions(array('administrate','edit','approve','manage_approval'),$loc);
		$template->output();
	}

	function searchName() {
		return 'RSS Feeds';
	}	

	function spiderContent($item = null) {
		global $db;
		
		if (!defined('SYS_SEARCH')) include_once(BASE.'subsystems/search.php');
		
		$search = null;
		$search->category = 'News';
		$search->ref_module = 'newsmodule';
		$search->ref_type = 'newsitem';
		
		if ($item) {
			$db->delete('search',"ref_module='newsmodule' AND ref_type='newsitem' AND original_id=" . $item->id);
			$search->original_id = $item->id;
			$search->title = ' ' . $item->title . ' ';
			$search->view_link = 'index.php?module=newsmodule&action=view&id='.$item->id;
			$search->body = ' ' . exponent_search_removeHTML($item->body) . ' ';
			$search->location_data = $item->location_data;
			$db->insertObject($search,'search');
		} else {
			$db->delete('search',"ref_module='newsmodule' AND ref_type='newsitem'");
			foreach ($db->selectObjects('newsitem') as $item) {
				$search->original_id = $item->id;
				$search->title = ' ' . $item->title . ' ';
				$search->view_link = 'index.php?module=newsmodule&action=view&id='.$item->id;
				$search->body = ' ' . exponent_search_removeHTML($item->body) . ' ';
				$search->location_data = $item->location_data;
				$db->insertObject($search,'search');
			}
		}
		
		return true;
	}

}

?>
