<?php

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