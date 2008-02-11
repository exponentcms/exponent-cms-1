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

$i18n = exponent_lang_loadFile('modules/administrationmodule/tasks/coretasks.php');

$stuff = array(
	$i18n['user_management']=>array(
		'useraccounts'=>array(
			'title'=>$i18n['user_accounts'],
			'module'=>'administrationmodule',
			'action'=>'useraccounts',
			'icon'=>ICON_RELATIVE."userperms.png"),
		'usersessions'=>array(
			'title'=>$i18n['user_sessions'],
			'module'=>'administrationmodule',
			'action'=>'usersessions'),
		'groupaccounts'=>array(
			'title'=>$i18n['group_accounts'],
			'module'=>'administrationmodule',
			'action'=>'groupaccounts',
			'icon'=>ICON_RELATIVE."groupperms.png"),
		'profiledefinitions'=>array(
			'title'=>$i18n['profile_definitions'],
			'module'=>'administrationmodule',
			'action'=>'profileext_manage'),
		'icon'=>ICON_RELATIVE."admin/users.png"
	),
	$i18n['extensions']=>array(
		'managemodules'=>array(
			'title'=>$i18n['manage_modules'],
			'module'=>'administrationmodule',
			'action'=>'managemodules',
			'icon'=>ICON_RELATIVE."modules.jpg"),
		'managethemes'=>array(
			'title'=>$i18n['manage_themes'],
			'module'=>'administrationmodule',
			'action'=>'managethemes'),
		'tags'=>array(
			'title'=>$i18n['manage_tags'],
			'module'=>'tags',
			'action'=>'manage'),
		'wizards'=>array(
			'title'=>$i18n['manage_wizards'],
			'module'=>'wizardmodule',
			'action'=>'manage_wizards'),
		'managesubsystems'=>array(
			'title'=>$i18n['manage_subsystems'],
			'module'=>'administrationmodule',
			'action'=>'managesubsystems'),
		'upload_extension'=>array(
			'title'=>$i18n['upload_extension'],
			'module'=>'administrationmodule',
			'action'=>'upload_extension'),
		'icon'=>ICON_RELATIVE."admin/extensions.png"
		// 'manage_bots'=>array(
		// 	'title'=>"Manage Bots",
		// 	'module'=>'bots',
		// 	'action'=>'manage_bots'),
		   
	),
	$i18n['database']=>array(
		'orphanedcontent'=>array(
			'title'=>$i18n['archived_modules'],
			'module'=>'administrationmodule',
			'action'=>'orphanedcontent'),
		'installdatabase'=>array(
			'title'=>$i18n['install_tables'],
			'module'=>'administrationmodule',
			'action'=>'installtables'),
		'trimdatabase'=>array(
			'title'=>$i18n['trim_database'],
			'module'=>'administrationmodule',
			'action'=>'trimdatabase'),
		'optimizedatabase'=>array(
			'title'=>$i18n['optimize_database'],
			'module'=>'administrationmodule',
			'action'=>'optimizedatabase'),
		'import'=>array(
			'title'=>$i18n['import_data'],
			'module'=>'importer',
			'action'=>'list_importers'),
		'export'=>array(
			'title'=>$i18n['export_data'],
			'module'=>'exporter',
			'action'=>'list_exporters'),
		'icon'=>ICON_RELATIVE."admin/database.png",
	),
	$i18n['configuration']=>array(
		'configuresite'=>array(
			'title'=>$i18n['configure_site'],
			'module'=>'administrationmodule',
			'action'=>'configuresite',
			'icon'=>ICON_RELATIVE."configure.png"),
		'mimetypes'=>array(
			'title'=>$i18n['file_types'],
			'module'=>'filemanager',
			'action'=>'admin_mimetypes',
			'icon'=>ICON_RELATIVE."filetypes.png"),
		'manage_policies'=>array(
			'title'=>$i18n['workflow_policies'],
			'module'=>'workflow',
			'action'=>'admin_manage_policies',
			'icon'=>ICON_RELATIVE."workflow.png"),
		'sysinfo'=>array(
			'title'=>$i18n['system_info'],
			'module'=>'administrationmodule',
			'action'=>'sysinfo',
			'icon'=>ICON_RELATIVE."system-info.png"),
		'icon'=>ICON_RELATIVE."admin/config.png",
	)
);
global $user;
if (!isset($user) || (isset($user->is_admin) && $user->is_admin == 0) ) {
 unset($stuff[$i18n['database']]['import']);
}
return $stuff;

?>
