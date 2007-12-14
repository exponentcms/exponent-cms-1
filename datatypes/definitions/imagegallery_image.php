<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# Exponent is distributed in the hope that it
# will be useful, but WITHOUT ANY WARRANTY;
# without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR
# PURPOSE.  See the GNU General Public License
# for more details.
#
# You should have received a copy of the GNU
# General Public License along with Exponent; if
# not, write to:
#
# Free Software Foundation, Inc.,
# 59 Temple Place,
# Suite 330,
# Boston, MA 02111-1307  USA
#
# $Id: imagegallery_image.php,v 1.5 2005/03/03 22:54:48 filetreefrog Exp $
##################################################

if (!defined('EXPONENT')) exit('');

return array(
	"id"=>array(
		DB_FIELD_TYPE=>DB_DEF_ID,
		DB_PRIMARY=>true,
		DB_INCREMENT=>true),
	"name"=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>150),
	"alt"=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>25),
	"description"=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>10000),
	"file_id"=>array(
		DB_FIELD_TYPE=>DB_DEF_ID),
	"thumbnail"=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>200),
	"gallery_id"=>array(
		DB_FIELD_TYPE=>DB_DEF_ID),
	"posted"=>array(
		DB_FIELD_TYPE=>DB_DEF_TIMESTAMP),
	"rank"=>array(
		DB_FIELD_TYPE=>DB_DEF_INTEGER),
	'newwindow'=>array(
		DB_FIELD_TYPE=>DB_DEF_BOOLEAN),
);

?>
