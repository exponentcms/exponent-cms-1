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
	'show_poster'=>array(
		DB_FIELD_TYPE=>DB_DEF_BOOLEAN),
	'allow_replys'=>array(
		DB_FIELD_TYPE=>DB_DEF_BOOLEAN),
	'allow_comments'=>array(
		DB_FIELD_TYPE=>DB_DEF_BOOLEAN),
	'approve_comments'=>array(
		DB_FIELD_TYPE=>DB_DEF_BOOLEAN),
	'require_login'=>array(
		DB_FIELD_TYPE=>DB_DEF_BOOLEAN),
	'use_captcha'=>array(
		DB_FIELD_TYPE=>DB_DEF_BOOLEAN),
	'comments_notify'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>200),
	'items_per_page'=>array(
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
	'rss_limit'=>array(
	    DB_FIELD_TYPE=>DB_DEF_INTEGER),
	'rss_cachetime'=>array(
	    DB_FIELD_TYPE=>DB_DEF_INTEGER),
	'aggregate'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>1000),
	"reply_title"=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>250),
	"email_title_post"=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>250),
	"email_from_post"=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>100),
	"email_address_post"=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>100),
	"email_reply_post"=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>100),
	"email_showpost_post"=>array(
		DB_FIELD_TYPE=>DB_DEF_BOOLEAN),
	"email_signature"=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>500),		
	'final_message'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>10000),
	'require_login_comments'=>array(
		DB_FIELD_TYPE=>DB_DEF_BOOLEAN),
);

?>
