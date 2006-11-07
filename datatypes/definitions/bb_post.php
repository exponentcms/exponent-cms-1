<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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
# $Id: bb_post.php,v 1.4 2005/02/19 16:53:34 filetreefrog Exp $
##################################################

if (!defined('EXPONENT')) exit('');

return array(
	"id"=>array(
		DB_FIELD_TYPE=>DB_DEF_ID,
		DB_PRIMARY=>true,
		DB_INCREMENT=>true),
	"parent"=>array(
		DB_FIELD_TYPE=>DB_DEF_ID),
	"board_id"=>array(
		DB_FIELD_TYPE=>DB_DEF_ID),
	"subject"=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>100),
	"body"=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>10000),
	"quote"=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>10000),
	"poster"=>array(
		DB_FIELD_TYPE=>DB_DEF_ID),
	"posted"=>array(
		DB_FIELD_TYPE=>DB_DEF_TIMESTAMP),
	"updated"=>array(
		DB_FIELD_TYPE=>DB_DEF_TIMESTAMP),
	"last_reply"=>array(
		DB_FIELD_TYPE=>DB_DEF_ID),
	"editor"=>array(
		DB_FIELD_TYPE=>DB_DEF_ID),
	"editted"=>array(
		DB_FIELD_TYPE=>DB_DEF_TIMESTAMP),
	"is_sticky"=>array(
		DB_FIELD_TYPE=>DB_DEF_BOOLEAN),
	"is_announcement"=>array(
		DB_FIELD_TYPE=>DB_DEF_BOOLEAN),
	"is_locked"=>array(
    DB_FIELD_TYPE=>DB_DEF_BOOLEAN),
	"num_views"=>array(
    DB_FIELD_TYPE=>DB_DEF_INTEGER),
	"num_replies"=>array(
		DB_FIELD_TYPE=>DB_DEF_INTEGER),
);

?>
