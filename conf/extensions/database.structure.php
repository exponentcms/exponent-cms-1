<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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