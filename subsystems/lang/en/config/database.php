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

define('TR_CONFIG_DATABASE_ERROR_BADPREFIX',		'Invalid table prefix.  The table prefix can only contain alphanumeric characters.');
define('TR_CONFIG_DATABASE_ERROR_CANTCONNECT',		'Unable to connect to database server.  Make sure that the database specified exists, and the user account specified has access to the server.');
define('TR_CONFIG_DATABASE_ERROR_PERMDENIED',		'Unable to run %s commands.');

define('TR_CONFIG_DATABASE_TITLE',					'Database Options');

define('TR_CONFIG_DATABASE_DB_ENGINE',				'Backend Software');
define('TR_CONFIG_DATABASE_DB_ENGINE_DESC',			'The database server software package.');

define('TR_CONFIG_DATABASE_DB_NAME',				'Database Name');
define('TR_CONFIG_DATABASE_DB_NAME_DESC',			'The name of the database to store the site tables in.');

define('TR_CONFIG_DATABASE_DB_USER',				'Username');
define('TR_CONFIG_DATABASE_DB_USER_DESC',			'The name of the user to connect to the database server as');

define('TR_CONFIG_DATABASE_DB_PASS',				'Password');
define('TR_CONFIG_DATABASE_DB_PASS_DESC',			'Password of the user above.');

define('TR_CONFIG_DATABASE_DB_HOST',				'Server Address');
define('TR_CONFIG_DATABASE_DB_HOST_DESC',			'The domain name or IP address of the database server.  If this is a local server, use "localhost"');

define('TR_CONFIG_DATABASE_DB_PORT',				'Server Port');
define('TR_CONFIG_DATABASE_DB_PORT_DESC',			'The port that the database server runs on.  For MySQL, this is 3306.');

define('TR_CONFIG_DATABASE_DB_TABLE_PREFIX',		'Table Prefix');
define('TR_CONFIG_DATABASE_DB_TABLE_PREFIX_DESC',	'A prefix to prepend to all table names.');


?>