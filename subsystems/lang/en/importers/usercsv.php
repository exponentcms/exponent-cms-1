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

define('TR_IMPORTER_USERCSV_NAME',								'User CSV Importer');
define('TR_IMPORTER_USERCSV_DESCRIPTION',						'This is an extension to import users from a csv (comma separated values) file');

define('TR_IMPORTER_USERCSV_DEMILITER_ARRAY_COMMA_KEY',			'Comma');
define('TR_IMPORTER_USERCSV_DEMILITER_ARRAY_SEMICOLON_KEY',		'Semicolon');
define('TR_IMPORTER_USERCSV_DEMILITER_ARRAY_COLON_KEY',			'Colon');
define('TR_IMPORTER_USERCSV_DEMILITER_ARRAY_SPACE_KEY',			'Space');

define('TR_IMPORTER_USERCSV_DEMILITER',							'Delimiter Character');
define('TR_IMPORTER_USERCSV_UPLOAD',							'CSV File to Upload');
define('TR_IMPORTER_USERCSV_ROWSTART',							'Start reading data from row number');

define('TR_IMPORTER_USERCSV_SUBMIT',							'Submit');
define('TR_IMPORTER_USERCSV_CANCEL',							'Cancel');

define('TR_IMPORTER_USERCSV_FILN',								'First Initial / Last Name');
define('TR_IMPORTER_USERCSV_FILNNUM',							'First Initial / Last Name / Random Number');
define('TR_IMPORTER_USERCSV_EMAIL',								'Email Address');
define('TR_IMPORTER_USERCSV_FNLN',								'First Name / Last Name');
define('TR_IMPORTER_USERCSV_UNAMEINFILE',						'Username Specified in CSV File');

define('TR_IMPORTER_USERCSV_RAND',								'Generate Random Passwords');
define('TR_IMPORTER_USERCSV_DEFPASS',							'Use the Default Password Supplied Below');
define('TR_IMPORTER_USERCSV_PWORDINFILE',						'Password Specified in CSV File');

define('TR_IMPORTER_USERCSV_UNAMEOPTIONS',						'User Name Generations Options');
define('TR_IMPORTER_USERCSV_PWORDOPTIONS',						'Password Generation Options');
define('TR_IMPORTER_USERCSV_PWORDTEXT',							'Default Password');
define('TR_IMPORTER_USERCSV_UPDATE',							'Update users already in database');

define('TR_IMPORTER_USERCSV_COLNAME_NONE',						'--Disregard this column--');
define('TR_IMPORTER_USERCSV_COLNAME_USERNAME',					'Username');
define('TR_IMPORTER_USERCSV_COLNAME_PASSWORD',					'Password');
define('TR_IMPORTER_USERCSV_COLNAME_FIRSTNAME',					'First Name');
define('TR_IMPORTER_USERCSV_COLNAME_LASTNAME',					'Last Name');
define('TR_IMPORTER_USERCSV_COLNAME_EMAIL',						'Email Address');

define('TR_IMPORTER_USERCSV_DELIMITER_ERROR',					'This file does not appear to be delimited by "%s". <br>Please specify a different delimiter.<br><br>');

?>