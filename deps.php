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

return array(
	array(
		'name'=>'autoloader',
		'type'=>CORE_EXT_SUBSYSTEM,
		'comment'=>''
	),
	array(
		'name'=>'core',
		'type'=>CORE_EXT_SUBSYSTEM,
		'comment'=>''
	),
	array(
		'name'=>'config',
		'type'=>CORE_EXT_SUBSYSTEM,
		'comment'=>''
	),
	array(
		'name'=>'database',
		'type'=>CORE_EXT_SUBSYSTEM,
		'comment'=>''
	),
	array(
		'name'=>'flow',
		'type'=>CORE_EXT_SUBSYSTEM,
		'comment'=>''
	),
	array(
		'name'=>'forms',
		'type'=>CORE_EXT_SUBSYSTEM,
		'comment'=>''
	),
	array(
		'name'=>'files',
		'type'=>CORE_EXT_SUBSYSTEM,
		'comment'=>''
	),
	array(
		'name'=>'info',
		'type'=>CORE_EXT_SUBSYSTEM,
		'comment'=>''
	),
	array(
		'name'=>'modules',
		'type'=>CORE_EXT_SUBSYSTEM,
		'comment'=>''
	),
	array(
		'name'=>'permissions',
		'type'=>CORE_EXT_SUBSYSTEM,
		'comment'=>''
	),
	array(
		'name'=>'template',
		'type'=>CORE_EXT_SUBSYSTEM,
		'comment'=>''
	),
	array(
		'name'=>'theme',
		'type'=>CORE_EXT_SUBSYSTEM,
		'comment'=>''
	),
	array(
		'name'=>'sessions',
		'type'=>CORE_EXT_SUBSYSTEM,
		'comment'=>''
	),
	array(
		'name'=>'sorting',
		'type'=>CORE_EXT_SUBSYSTEM,
		'comment'=>''
	),
	array(
		'name'=>'users',
		'type'=>CORE_EXT_SUBSYSTEM,
		'comment'=>''
	),
	array(
		'name'=>'workflow',
		'type'=>CORE_EXT_SUBSYSTEM,
		'comment'=>''
	),
	
	// Modules
	array(
		'name'=>'administrationmodule',
		'type'=>CORE_EXT_MODULE,
		'comment'=>''
	),
	array(
		'name'=>'common',
		'type'=>CORE_EXT_MODULE,
		'comment'=>''
	),
	array(
		'name'=>'workflow',
		'type'=>CORE_EXT_MODULE,
		'comment'=>''
	),
	array(
		'name'=>'filemanager',
		'type'=>CORE_EXT_MODULE,
		'comment'=>''
	),
	array(
		'name'=>'info',
		'type'=>CORE_EXT_MODULE,
		'comment'=>''
	),
	
	array(
		'name'=>'portaltheme',
		'type'=>CORE_EXT_THEME,
		'comment'=>''
	)
);

?>
