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
		DB_FIELD_LEN=>100,
		DB_INDEX=>10),
	'bgcolor'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>12),
	'height'=>array(
		DB_FIELD_TYPE=>DB_DEF_INTEGER),
	'width'=>array(
		DB_FIELD_TYPE=>DB_DEF_INTEGER),
	'alignment'=>array(
		DB_FIELD_TYPE=>DB_DEF_INTEGER),
	'swf_id'=>array(
		DB_FIELD_TYPE=>DB_DEF_ID),
	'alt_image_id'=>array(
		DB_FIELD_TYPE=>DB_DEF_ID),
	'location_data'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>200),
	'loop_movie'=>array(
		DB_FIELD_TYPE=>DB_DEF_BOOLEAN)
);
?>