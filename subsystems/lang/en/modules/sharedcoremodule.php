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
# $Id$
##################################################

// I18n constants for Shared Core Module

// Permissions
define('TR_SHAREDCOREMODULE_PERM_ADMIN',			'Administrate');
define('TR_SHAREDCOREMODULE_PERM_CONFIG',			'Configure');

define('TR_SHAREDCOREMODULE_CORENAME',				'Core Name');
define('TR_SHAREDCOREMODULE_COREPATH',				'Path');

define('TR_SHAREDCOREMODULE_SITECORE',				'Codebase');
define('TR_SHAREDCOREMODULE_SITENAME',				'Site Name');
define('TR_SHAREDCOREMODULE_SITEPATH',				'Path');

define('TR_SHAREDCOREMODULE_MODULES',				'Modules');
define('TR_SHAREDCOREMODULE_THEMES',				'Themes');

define('TR_SHAREDCOREMODULE_TEMPLATE',				'Template');
define('TR_SHAREDCOREMODULE_REASON',				'Reason');
define('TR_SHAREDCOREMODULE_DEACTIVATE',			'Deactivate');

define('TR_SHAREDCOREMODULE_ERR_COREEXISTS',		'A core has already been defined for the path "%s"');
define('TR_SHAREDCOREMODULE_ERR_INVALIDCORE',		'The path you specified ("%s") does not appear to be a valid installation of Exponent.');

define('TR_SHAREDCOREMODULE_ERR_NOTREADABLENOW',	'Core is no longer readable.');
define('TR_SHAREDCOREMODULE_ERR_NOTEXISTSNOW',		'Core is no longer exists.');

define('TR_SHAREDCOREMODULE_ERR_DESTEXISTS',		'There is already an Exponent installation (version %s) in "%s"');
define('TR_SHAREDCOREMODULE_ERR_DESTNOTWRITABLE',	'The path you specified for the root of the new site ("%s") is not writable.');

?>