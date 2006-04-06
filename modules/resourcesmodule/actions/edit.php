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

if (!defined('EXPONENT')) exit('');

$resource = null;
$iloc = null;
if (isset($_GET['id'])) {
	$resource = $db->selectObject('resourceitem','id='.intval($_GET['id']));
	if ($resource) {
		$loc = unserialize($resource->location_data);
		$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$resource->id);
	}
}

if (($resource == null && exponent_permissions_check('post',$loc)) ||
	($resource != null && exponent_permissions_check('edit',$loc)) ||
	($iloc != null && exponent_permissions_check('edit',$iloc))
) {
	$form = resourceitem::form($resource);
	$form->location($loc);
	$form->meta('action','save');
	
	$template = new template('resourcesmodule','_form_edit',$loc);
	
	if (!isset($resource->id)) {
		$ranks = array();
		foreach ($db->selectObjects('resourceitem',"location_data='".serialize($loc)."'") as $item) {
			$ranks[$item->rank+1] = 'After "'.$item->name.'"';
		}
		$ranks[0] = 'At The Top';
		ksort($ranks);
		$form->registerBefore('submit','rank','Position',new dropdowncontrol(count($ranks)-1,$ranks));
	}
	
	if (!isset($resource->file_id)) {
		$i18n = exponent_lang_loadFile('modules/resourcesmodule/actions/edit.php');
		
		$form->registerBefore('submit','file',$i18n['file'],new uploadcontrol());
		
		$dir = 'files/resourcesmodule/'.$loc->src;
		if (!is_really_writable(BASE.$dir)) {
			$template->assign('dir_not_readable',1);
			$form->controls['submit']->disabled = true;
		} else {
			$template->assign('dir_not_readable',0);
		}
	}
	
	$template->assign('form_html',$form->toHTML());
	$template->assign('is_edit', (isset($_GET['id']) ? 1 : 0) );
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
