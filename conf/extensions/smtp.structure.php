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

if (!defined('PATHOS')) exit('');

pathos_lang_loadDictionary('config','smtp');

return array(
	TR_CONFIG_SMTP_TITLE,
	array(
		'SMTP_SERVER'=>array(
			'title'=>TR_CONFIG_SMTP_SERVER,
			'description'=>TR_CONFIG_SMTP_SERVER_DESC,
			'control'=>new textcontrol()
		),
		'SMTP_PORT'=>array(
			'title'=>TR_CONFIG_SMTP_PORT,
			'description'=>TR_CONFIG_SMTP_PORT_DESC,
			'control'=>new textcontrol()
		),
		'SMTP_AUTHTYPE'=>array(
			'title'=>TR_CONFIG_SMTP_AUTHTYPE,
			'description'=>TR_CONFIG_SMTP_AUTHTYPE_DESC,
			'control'=>new dropdowncontrol('',array('NONE'=>'No Authentication','LOGIN'=>'LOGIN','PLAIN'=>'PLAIN'))
		),
		'SMTP_USERNAME'=>array(
			'title'=>TR_CONFIG_SMTP_USERNAME,
			'description'=>TR_CONFIG_SMTP_USERNAME_DESC,
			'control'=>new textcontrol()
		),
		'SMTP_PASSWORD'=>array(
			'title'=>TR_CONFIG_SMTP_PASSWORD,
			'description'=>TR_CONFIG_SMTP_PASSWORD_DESC,
			'control'=>new passwordcontrol()
		),
		'SMTP_FROMADDRESS'=>array(
			'title'=>TR_CONFIG_SMTP_ADDRESS,
			'description'=>TR_CONFIG_SMTP_ADDRESS_DESC,
			'control'=>new textcontrol()
		),
	)
);

?>