<?php

return array(
	'id'=>array(
		DB_FIELD_TYPE=>DB_DEF_ID,
		DB_PRIMARY=>true,
		DB_INCREMENT=>true),
	'location_data'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>250),
	'name'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>250),
	'description'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>25000),
	'priority'=>array(
		DB_FIELD_TYPE=>DB_DEF_INTEGER),
	'completion'=>array(
		DB_FIELD_TYPE=>DB_DEF_INTEGER)
);

?>