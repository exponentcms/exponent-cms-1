<?php

##################################################
#
# Copyright 2004 James Hunt and OIC Group, Inc.
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

pathos_lang_loadDictionary('admintasks','coretasks');

$stuff = array(
	TR_CORETASKS_CAT_USERMANAGEMENT=>array(
		'useraccounts'=>array(
			'title'=>TR_CORETASKS_ITEM_USERACCOUNTS,
			'module'=>'administrationmodule',
			'action'=>'useraccounts'),
		'usersessions'=>array(
			'title'=>TR_CORETASKS_ITEM_USERSESSIONS,
			'module'=>'administrationmodule',
			'action'=>'usersessions'),
		'groupaccounts'=>array(
			'title'=>TR_CORETASKS_ITEM_GROUPACCOUNTS,
			'module'=>'administrationmodule',
			'action'=>'groupaccounts'),
		'profiledefinitions'=>array(
			'title'=>TR_CORETASKS_ITEM_PROFILEDEFS,
			'module'=>'administrationmodule',
			'action'=>'profileext_manage')
	),
	TR_CORETASKS_CAT_EXT=>array(
		'managemodules'=>array(
			'title'=>TR_CORETASKS_ITEM_MANAGEMODULES,
			'module'=>'administrationmodule',
			'action'=>'managemodules'),
		'managethemes'=>array(
			'title'=>TR_CORETASKS_ITEM_MANAGETHEMES,
			'module'=>'administrationmodule',
			'action'=>'managethemes'),
		'managesubsystems'=>array(
			'title'=>TR_CORETASKS_ITEM_MANAGESUBSYSTEMS,
			'module'=>'administrationmodule',
			'action'=>'managesubsystems'),
		'upload_extension'=>array(
			'title'=>TR_CORETASKS_ITEM_UPLOADEXT,
			'module'=>'administrationmodule',
			'action'=>'upload_extension')
	),
	TR_CORETASKS_CAT_DATABASE=>array(
		'orphanedcontent'=>array(
			'title'=>TR_CORETASKS_ITEM_ARCHIVEDMODS,
			'module'=>'administrationmodule',
			'action'=>'orphanedcontent'),
		'installdatabase'=>array(
			'title'=>TR_CORETASKS_ITEM_INSTALLTABLES,
			'module'=>'administrationmodule',
			'action'=>'installtables'),
		'trimdatabase'=>array(
			'title'=>TR_CORETASKS_ITEM_TRIMDB,
			'module'=>'administrationmodule',
			'action'=>'trimdatabase'),
		'optimizedatabase'=>array(
			'title'=>TR_CORETASKS_ITEM_OPTIMIZEDB,
			'module'=>'administrationmodule',
			'action'=>'optimizedatabase'),
		'import'=>array(
			'title'=>TR_CORETASKS_ITEM_IMPORTDATA,
			'module'=>'importer',
			'action'=>'list_importers'),
		'export'=>array(
			'title'=>TR_CORETASKS_ITEM_EXPORTDATA,
			'module'=>'exporter',
			'action'=>'list_exporters'),
	),
	TR_CORETASKS_CAT_CONFIG=>array(
		'configuresite'=>array(
			'title'=>TR_CORETASKS_ITEM_CONFIGSITE,
			'module'=>'administrationmodule',
			'action'=>'configuresite'),
		'sysinfo'=>array(
			'title'=>TR_CORETASKS_ITEM_SYSINFO,
			'module'=>'administrationmodule',
			'action'=>'sysinfo')
	)
);
global $user;
if (!$user || $user->is_admin == 0) {
	unset($stuff[TR_CORETASKS_CAT_DATABASE]['import']);
}

return $stuff;

?>