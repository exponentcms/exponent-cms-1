<?php

##################################################
# __XYZZY_BOILER
# $Id$
##################################################

return array(
	"id"=>array(
		DB_FIELD_TYPE=>DB_DEF_ID,
		DB_PRIMARY=>true,
		DB_INCREMENT=>true),
	"name"=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>100),
	"affiliate_id"=>array(
		DB_FIELD_TYPE=>DB_DEF_ID),
	"location_data"=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>250),
	"file_id"=>array(
		DB_FIELD_TYPE=>DB_DEF_ID),
	"url"=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>1000),
);

?>