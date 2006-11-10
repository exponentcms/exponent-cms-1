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
	'location_data'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>200,
 	        DB_INDEX=>10),
	'sortorder'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>100),
	'sortfield'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>100),
	'item_limit'=>array(
		DB_FIELD_TYPE=>DB_DEF_INTEGER),
	'enable_rss'=>array(
		DB_FIELD_TYPE=>DB_DEF_BOOLEAN),
	'enable_tags'=>array(
		DB_FIELD_TYPE=>DB_DEF_BOOLEAN),
	'collections'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>1000,
 	        DB_INDEX=>10),
	'group_by_tags'=>array(
		DB_FIELD_TYPE=>DB_DEF_BOOLEAN),
	'show_tags'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>1000,
 	        DB_INDEX=>10),
	'feed_title'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>75),
	'feed_desc'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>200),
);

?>
