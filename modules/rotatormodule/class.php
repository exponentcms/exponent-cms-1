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

class rotatormodule {
	function name() { return exponent_lang_loadKey('modules/rotatormodule/class.php','module_name'); }
	function description() { return exponent_lang_loadKey('modules/rotatormodule/class.php','module_description'); }
	function author() { return 'OIC Group, Inc'; }
	
	function hasSources() { return true; }
	function hasContent() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = '') {
		$i18n = exponent_lang_loadFile('modules/rotatormodule/class.php');
		return array(
			'administrate'=>$i18n['perm_administrate'],
			'manage'=>$i18n['perm_manage'],
		);
	}
	
	function show($view,$loc = null, $title = "") {
		global $db;
		
		$obj = null;
		$o = $db->selectObjects('rotator_item',"location_data='".serialize($loc)."'");
		$num = mt_rand(0,count($o)-1);
		if (isset($o[$num])) {
			$obj = $o[$num];
		} else {
			$obj->text = '';
		}
		$template = new template('rotatormodule',$view,$loc);
		$template->assign('moduletitle',$title);
		$template->assign('content',$obj);
		$template->register_permissions(
			array('administrate','manage'),
			$loc);
		$template->output();
	}
	
	function deleteIn($loc) {
		global $db;
		$db->delete('rotator_item',"location_data='".serialize($loc)."'");
	}
	
	function copyContent($oloc,$nloc) {
		global $db;
		foreach ($db->selectObject('rotator_item',"location_data='".serialize($oloc)."'") as $item) {
			unset($item->id);
			$item->location_data = serialize($nloc);
			$db->insertObject($item,'rotator_item');
		}
	}
	
	function spiderContent($item = null) {
		// Do nothing, no content
		return false;
	}
	
}

?>
