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

class filemanagermodule {
	function name() { return exponent_lang_loadKey('modules/filemanagermodule/class.php','module_name'); }
	function author() { return exponent_lang_loadKey('modules/filemanagermodule/class.php','module_author'); }
	function description() { return exponent_lang_loadKey('modules/filemanagermodule/class.php','module_description'); }

	function hasSources() { return false; }
	function hasContent() { return true; }
	function hasViews() { return true; }

	function supportsWorkflow() { return false; }

	function permissions($internal = "") {
		$i18n = exponent_lang_loadFile('modules/filemanagermodule/class.php');
		
		if ($internal == "") {
			return array(
				'administrate'=>$i18n['perm_administrate'],
				'configure'=>$i18n['perm_configure'],
			);
		} else {
			return array(
				'administrate'=>$i18n['perm_administrate'],
				'configure'=>$i18n['perm_configure'],
			);
		}
	}

	function show($view,$loc = null, $title = "") {
		$loc = exponent_core_makeLocation('filemanagermodule');

		global $db;
		$collections = $db->selectObjects('file_collection');

		$template = new template('filemanagermodule',$view,$loc);
		$template->assign('collections',$collections);

		$template->output();
	}

	function deleteIn($loc) {

	}

	function copyContent($oloc,$nloc) {

	}
}

?>