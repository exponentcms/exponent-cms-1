<?php

return array(
	'id'=>array(
		DB_FIELD_TYPE=>DB_DEF_ID,
		DB_PRIMARY=>true,
		DB_INCREMENT=>true),
	'location_data'=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>250),
	'show_completed'=>array(
		DB_FIELD_TYPE=>DB_DEF_BOOLEAN)
);

?>