<?php

return array(
	'id'=>array(
		DB_FIELD_TYPE=>DB_DEF_ID,
		DB_PRIMARY=>true,
		DB_INCREMENT=>true),
	'location_data'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>250),
	'orderby'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>100),
	'orderhow'=>array(
		DB_FIELD_TYPE=>DB_DEF_INTEGER),
);

?>