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
		DB_FIELD_LEN=>1000),

	'max_approvers'=>array(
		DB_FIELD_TYPE=>DB_DEF_INTEGER),
	'required_approvals'=>array(
		DB_FIELD_TYPE=>DB_DEF_INTEGER),
	'on_deny'=>array(
		DB_FIELD_TYPE=>DB_DEF_INTEGER),
	'delete_on_deny'=>array(
		DB_FIELD_TYPE=>DB_DEF_BOOLEAN),
	'on_edit'=>array(
		DB_FIELD_TYPE=>DB_DEF_INTEGER),
	'on_approve'=>array(
		DB_FIELD_TYPE=>DB_DEF_INTEGER)
);

?>