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

$i18n = pathos_lang_loadFile('modules/administrationmodule/tasks/coretasks.php');

$stuff = array(
	$i18n['user_management']=>array(
		'useraccounts'=>array(
			'title'=>$i18n['user_accounts'],
			'module'=>'administrationmodule',
			'action'=>'useraccounts'),
		'usersessions'=>array(
			'title'=>$i18n['user_sessions'],
			'module'=>'administrationmodule',
			'action'=>'usersessions'),
		'groupaccounts'=>array(
			'title'=>$i18n['group_accounts'],
			'module'=>'administrationmodule',
			'action'=>'groupaccounts'),
		'profiledefinitions'=>array(
			'title'=>$i18n['profile_definitions'],
			'module'=>'administrationmodule',
			'action'=>'profileext_manage')
	),
	$i18n['extensions']=>array(
		'managemodules'=>array(
			'title'=>$i18n['manage_modules'],
			'module'=>'administrationmodule',
			'action'=>'managemodules'),
		'managethemes'=>array(
			'title'=>$i18n['manage_themes'],
			'module'=>'administrationmodule',
			'action'=>'managethemes'),
		'managesubsystems'=>array(
			'title'=>$i18n['manage_subsystems'],
			'module'=>'administrationmodule',
			'action'=>'managesubsystems'),
		'upload_extension'=>array(
			'title'=>$i18n['upload_extension'],
			'module'=>'administrationmodule',
			'action'=>'upload_extension')
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
	),
	$i18n['configuration']=>array(
		'configuresite'=>array(
			'title'=>$i18n['configure_site'],
			'module'=>'administrationmodule',
			'action'=>'configuresite'),
		'mimetypes'=>array(
			'title'=>$i18n['file_types'],
			'module'=>'filemanager',
			'action'=>'admin_mimetypes'),
		'manage_policies'=>array(
			'title'=>$i18n['workflow_policies'],
			'module'=>'workflow',
			'action'=>'admin_manage_policies'),
		'sysinfo'=>array(
			'title'=>$i18n['system_info'],
			'module'=>'administrationmodule',
			'action'=>'sysinfo'),
	)
);
global $user;
if (!$user || $user->is_admin == 0) {
	unset($stuff[$i18n['database']]['import']);
}

return $stuff;

?>