<?php

return array(
	'id'=>array(
		DB_FIELD_TYPE=>DB_DEF_ID,
		DB_PRIMARY=>true,
		DB_INCREMENT=>true),
	'tablename'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>200),
	'titlefield'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>100),
	'viewlink'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>512),
	'item_id'=>array(
		DB_FIELD_TYPE=>DB_DEF_ID),
	'published_id'=>array(
		DB_FIELD_TYPE=>DB_DEF_ID),
	'status'=>array(
		DB_FIELD_TYPE=>DB_DEF_INTEGER),
	'channel_id'=>array(
		DB_FIELD_TYPE=>DB_DEF_ID),
);

?>