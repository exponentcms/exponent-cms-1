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
	'uid'=>array(
		DB_FIELD_TYPE=>DB_DEF_ID,
		DB_PRIMARY=>true),
	'icq_num'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>25),
	'aim_addy'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>25),
	'msn_addy'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>25),
	'yahoo_addy'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>25),
	'skype_addy'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>25),
	'gtalk_addy'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>25),
	'website'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>55),
	'location'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>55),
	'occupation'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>55),
	"interests"=>array(
                DB_FIELD_TYPE=>DB_DEF_STRING,
                DB_FIELD_LEN=>10000),
	"signature"=>array(
                DB_FIELD_TYPE=>DB_DEF_STRING,
                DB_FIELD_LEN=>10000),
	"show_email_addy"=>array(
                DB_FIELD_TYPE=>DB_DEF_BOOLEAN),
	"hide_online_status"=>array(
                DB_FIELD_TYPE=>DB_DEF_BOOLEAN),
	"notify_of_replies"=>array(
                DB_FIELD_TYPE=>DB_DEF_BOOLEAN),
	"notify_of_pvt_msg"=>array(
                DB_FIELD_TYPE=>DB_DEF_BOOLEAN),
	"attach_signature"=>array(
                DB_FIELD_TYPE=>DB_DEF_BOOLEAN),
	"num_posts"=>array(
		DB_FIELD_TYPE=>DB_DEF_INTEGER),
	'file_id'=>array(
                DB_FIELD_TYPE=>DB_DEF_ID)
);

?>
