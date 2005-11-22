<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
# All Changes as of 6/1/05 Copyright 2005 James Hunt
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

if (!defined('PATHOS')) exit('');

$resource = null;
$iloc = null;
if (isset($_GET['id'])) {
	$resource = $db->selectObject('resourceitem','id='.intval($_GET['id']));
	if ($resource) {
		$loc = unserialize($resource->location_data);
		$iloc = pathos_core_makeLocation($loc->mod,$loc->src,$resource->id);
	}
}

if (($resource == null && pathos_permissions_check('post',$loc)) ||
	($resource != null && pathos_permissions_check('edit',$loc)) ||
	($iloc != null && pathos_permissions_check('edit',$iloc))
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
		$i18n = pathos_lang_loadFile('modules/resourcesmodule/actions/edit.php');
		
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
