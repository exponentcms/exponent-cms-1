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

if (!defined('PATHOS')) exit('');

return array(
	'ticket'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>23, // uniqid('',true) returns 23-char strings
		DB_PRIMARY=>true),
	'uid'=>array(
		DB_FIELD_TYPE=>DB_DEF_ID),
	'last_active'=>array(
		DB_FIELD_TYPE=>DB_DEF_TIMESTAMP),
	'refresh'=>array(
		DB_FIELD_TYPE=>DB_DEF_BOOLEAN),
	'ip_address'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>15),
	'start_time'=>array(
		DB_FIELD_TYPE=>DB_DEF_TIMESTAMP),
	'browser'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>250),
/*	'last_section'=>array(
		DB_FIELD_TYPE=>DB_DEF_INTEGER),
	'last_action'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>100),
	'last_module'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>100),
	'last_action_descriptive'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>250)
*/	
);

?>