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
	'name'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>100),
	'description'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>10000),
	'location_data'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>250,
                DB_INDEX=>10),
	'file_id'=>array(
		DB_FIELD_TYPE=>DB_DEF_ID),
	'flock_owner'=>array(
		DB_FIELD_TYPE=>DB_DEF_ID),
	'approved'=>array(
		DB_FIELD_TYPE=>DB_DEF_INTEGER),
	'posted'=>array(
		DB_FIELD_TYPE=>DB_DEF_TIMESTAMP),
	'poster'=>array(
		DB_FIELD_TYPE=>DB_DEF_ID),
	'edited'=>array(
		DB_FIELD_TYPE=>DB_DEF_TIMESTAMP),
	'editor'=>array(
		DB_FIELD_TYPE=>DB_DEF_ID),
	'rank'=>array(
		DB_FIELD_TYPE=>DB_DEF_INTEGER),
	'num_downloads'=>array(
		DB_FIELD_TYPE=>DB_DEF_INTEGER),
);

?>
