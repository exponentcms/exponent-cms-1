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

return array(
	'id'=>array(
		DB_FIELD_TYPE=>DB_DEF_ID,
		DB_PRIMARY=>true,
		DB_INCREMENT=>true),
	'title'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>150),
	'internal_name'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>150,
		DB_INDEX=>10),
	'body'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>10000),
	'publish'=>array(
		DB_FIELD_TYPE=>DB_DEF_TIMESTAMP),
	'unpublish'=>array(
		DB_FIELD_TYPE=>DB_DEF_TIMESTAMP),
	'is_private'=>array(
		DB_FIELD_TYPE=>DB_DEF_BOOLEAN),
	'poster'=>array(
		DB_FIELD_TYPE=>DB_DEF_ID),
	'posted'=>array(
		DB_FIELD_TYPE=>DB_DEF_TIMESTAMP,
		DB_INDEX=>0),
	'edited'=>array(
		DB_FIELD_TYPE=>DB_DEF_TIMESTAMP),
	'editor'=>array(
		DB_FIELD_TYPE=>DB_DEF_ID),
	'location_data'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>200,
		DB_INDEX=>10),
	'is_draft'=>array(
		DB_FIELD_TYPE=>DB_DEF_BOOLEAN),
	'reads'=>array(
		DB_FIELD_TYPE=>DB_DEF_INTEGER),		
	'file_id'=>array(
    	DB_FIELD_TYPE=>DB_DEF_ID),
	'tags'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>10000)
);

?>
