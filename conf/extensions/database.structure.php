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

pathos_lang_loadDictionary('config','database');

return array(
	TR_CONFIG_DATABASE_TITLE,
	array(
		'DB_ENGINE'=>array(
			'title'=>TR_CONFIG_DATABASE_DB_ENGINE,
			'description'=>TR_CONFIG_DATABASE_DB_ENGINE_DESC,
			'control'=>new dropdowncontrol('',pathos_database_backends())
		),
		'DB_NAME'=>array(
			'title'=>TR_CONFIG_DATABASE_DB_NAME,
			'description'=>TR_CONFIG_DATABASE_DB_NAME_DESC,
			'control'=>new textcontrol()
		),
		'DB_USER'=>array(
			'title'=>TR_CONFIG_DATABASE_DB_USER,
			'description'=>TR_CONFIG_DATABASE_DB_USER_DESC,
			'control'=>new textcontrol()
		),
		'DB_PASS'=>array(
			'title'=>TR_CONFIG_DATABASE_DB_PASS,
			'description'=>TR_CONFIG_DATABASE_DB_PASS_DESC,
			'control'=>new passwordcontrol()
		),
		'DB_HOST'=>array(
			'title'=>TR_CONFIG_DATABASE_DB_HOST,
			'description'=>TR_CONFIG_DATABASE_DB_HOST_DESC,
			'control'=>new textcontrol()
		),
		'DB_PORT'=>array(
			'title'=>TR_CONFIG_DATABASE_DB_PORT,
			'description'=>TR_CONFIG_DATABASE_DB_PORT_DESC,
			'control'=>new textcontrol()
		),
		'DB_TABLE_PREFIX'=>array(
			'title'=>TR_CONFIG_DATABASE_DB_TABLE_PREFIX,
			'description'=>TR_CONFIG_DATABASE_DB_TABLE_PREFIX_DESC,
			'control'=>new textcontrol()
		)
	)
);

?>