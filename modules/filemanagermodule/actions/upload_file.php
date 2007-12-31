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

if (exponent_users_isLoggedIn()) {
	$_GET['id'] = intval($_GET['id']);
	
	$collection = null;
	if (isset($_GET['id'])) {
		$collection = $db->selectObject('file_collection','id='.$_GET['id']);
	}
	$loc = exponent_core_makeLocation('filemanagermodule');

	if ($collection) {
		// PERM CHECK
			if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
			exponent_forms_initialize();
		
			$form = new form();
			$form->meta('module','filemanagermodule');
			$form->meta('action','save_upload');
			$form->meta('collection_id',$collection->id);
			
			$form->register('name','Name',new textcontrol());
			$form->register('file','File',new uploadcontrol());
			$form->register('submit','',new buttongroupcontrol('Save','','Cancel'));
		
			echo $form->toHTML();
		// END PERM CHECK
	} else {
		echo SITE_404_HTML;
	}
} else {
	echo "Action not allowed";
}

?>
