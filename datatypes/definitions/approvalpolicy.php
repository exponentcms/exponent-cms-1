<?php

##################################################
#
# Copyright 2004 James Hunt and OIC Group, Inc.
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
	"description"=>array(
		DB_FIELD_TYPE=>DB_DEF_STRING,
		DB_FIELD_LEN=>1000),

	"max_approvers"=>array(
		DB_FIELD_TYPE=>DB_DEF_INTEGER),
	"required_approvals"=>array(
		DB_FIELD_TYPE=>DB_DEF_INTEGER),
	"on_deny"=>array(
		DB_FIELD_TYPE=>DB_DEF_INTEGER),
	"delete_on_deny"=>array(
		DB_FIELD_TYPE=>DB_DEF_BOOLEAN),
	"on_edit"=>array(
		DB_FIELD_TYPE=>DB_DEF_INTEGER),
	"on_approve"=>array(
		DB_FIELD_TYPE=>DB_DEF_INTEGER)
);

?>