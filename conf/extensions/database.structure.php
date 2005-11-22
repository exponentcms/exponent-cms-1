<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
# All Changes as of 6/1/05 Copyright 2005 James Hunt
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

if (!defined('PATHOS')) exit('');

$i18n = pathos_lang_loadFile('conf/extensions/database.structure.php');

return array(
	$i18n['title'],
	array(
		'DB_ENGINE'=>array(
			'title'=>$i18n['db_engine'],
			'description'=>$i18n['db_engine_desc'],
			'control'=>new dropdowncontrol('',pathos_database_backends())
		),
		'DB_NAME'=>array(
			'title'=>$i18n['db_name'],
			'description'=>$i18n['db_name_desc'],
			'control'=>new textcontrol()
		),
		'DB_USER'=>array(
			'title'=>$i18n['db_user'],
			'description'=>$i18n['db_user_desc'],
			'control'=>new textcontrol()
		),
		'DB_PASS'=>array(
			'title'=>$i18n['db_pass'],
			'description'=>$i18n['db_pass_desc'],
			'control'=>new passwordcontrol()
		),
		'DB_HOST'=>array(
			'title'=>$i18n['db_host'],
			'description'=>$i18n['db_host_desc'],
			'control'=>new textcontrol()
		),
		'DB_PORT'=>array(
			'title'=>$i18n['db_port'],
			'description'=>$i18n['db_port_desc'],
			'control'=>new textcontrol()
		),
		'DB_TABLE_PREFIX'=>array(
			'title'=>$i18n['db_table_prefix'],
			'description'=>$i18n['db_table_prefix_desc'],
			'control'=>new textcontrol()
		)
	)
);

?>