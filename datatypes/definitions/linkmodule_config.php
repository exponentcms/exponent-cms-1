<?php

if (!defined('EXPONENT')) exit('');

return array(
	"id"=>array(
		DB_FIELD_TYPE=>DB_DEF_ID,
		DB_PRIMARY=>true,
		DB_INCREMENT=>true),
	"location_data"=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>200),
	"open_in_a_new_window"=>array(
		DB_FIELD_TYPE=>DB_DEF_BOOLEAN),
	'enable_rss'=>array(
		DB_FIELD_TYPE=>DB_DEF_BOOLEAN),
	'feed_title'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>75),
	'feed_desc'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>200),
	'rss_categories'=>array(
		DB_FIELD_TYPE=>DB_DEF_BOOLEAN),
	'rss_add_category_name'=>array(
		DB_FIELD_TYPE=>DB_DEF_BOOLEAN),
	'enable_rss_categories'=>array(
		DB_FIELD_TYPE=>DB_DEF_BOOLEAN),
	'orderby'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>100),
	'orderhow'=>array(
		DB_FIELD_TYPE=>DB_DEF_INTEGER),  
	"enable_categories"=>array(
		DB_FIELD_TYPE=>DB_DEF_INTEGER),		
);

?>