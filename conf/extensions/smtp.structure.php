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

$i18n = pathos_lang_loadFile('conf/extensions/smtp.structure.php');

return array(
	$i18n['title'],
	array(
		'SMTP_USE_PHP_MAIL'=>array(
			'title'=>$i18n['php_mail'],
			'description'=>$i18n['php_mail_desc'],
			'control'=>new checkboxcontrol()
		),
		'SMTP_SERVER'=>array(
			'title'=>$i18n['server'],
			'description'=>$i18n['server_desc'],
			'control'=>new textcontrol()
		),
		'SMTP_PORT'=>array(
			'title'=>$i18n['port'],
			'description'=>$i18n['port_desc'],
			'control'=>new textcontrol()
		),
		'SMTP_AUTHTYPE'=>array(
			'title'=>$i18n['auth'],
			'description'=>$i18n['auth_desc'],
			'control'=>new dropdowncontrol('',array('NONE'=>$i18n['auth_none'],'LOGIN'=>$i18n['auth_login'],'PLAIN'=>$i18n['auth_plain']))
		),
		'SMTP_USERNAME'=>array(
			'title'=>$i18n['username'],
			'description'=>$i18n['username_desc'],
			'control'=>new textcontrol()
		),
		'SMTP_PASSWORD'=>array(
			'title'=>$i18n['password'],
			'description'=>$i18n['password'],
			'control'=>new passwordcontrol()
		),
		'SMTP_FROMADDRESS'=>array(
			'title'=>$i18n['from_address'],
			'description'=>$i18n['from_address_desc'],
			'control'=>new textcontrol()
		),
	)
);

?>