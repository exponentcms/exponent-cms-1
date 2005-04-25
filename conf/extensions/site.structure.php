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
# $Id$
##################################################

if (!defined('PATHOS')) exit('');

pathos_lang_loadDictionary('config','site');

$stuff = array(
	TR_CONFIG_SITE_TITLE,
	array(
		'SITE_TITLE'=>array(
			'title'=>TR_CONFIG_SITE_SITE_TITLE,
			'description'=>TR_CONFIG_SITE_SITE_TITLE_DESC,
			'control'=>new textcontrol()
		),
		'USE_LANG'=>array(
			'title'=>TR_CONFIG_SITE_USE_LANG,
			'description'=>TR_CONFIG_SITE_USE_LANG,
			'control'=>new dropdowncontrol(0,pathos_lang_list())
		),
		'SITE_ALLOW_REGISTRATION'=>array(
			'title'=>TR_CONFIG_SITE_ALLOW_REGISTRATION,
			'description'=>TR_CONFIG_SITE_ALLOW_REGISTRATION_DESC,
			'control'=>new checkboxcontrol()
		),
		'SITE_USE_CAPTCHA'=>array(
			'title'=>TR_CONFIG_SITE_USE_CAPTCHA,
			'description'=>TR_CONFIG_SITE_USE_CAPTCHA_DESC,
			'control'=>new checkboxcontrol()
		),
		'SITE_KEYWORDS'=>array(
			'title'=>TR_CONFIG_SITE_KEYWORDS,
			'description'=>TR_CONFIG_SITE_KEYWORDS_DESC,
			'control'=>new texteditorcontrol('',10,30)
		),
		'SITE_DESCRIPTION'=>array(
			'title'=>TR_CONFIG_SITE_DESCRIPTION,
			'description'=>TR_CONFIG_SITE_DESCRIPTION_DESC,
			'control'=>new texteditorcontrol('',15,50)
		),
		'SITE_404_HTML'=>array(
			'title'=>TR_CONFIG_SITE_404,
			'description'=>TR_CONFIG_SITE_404_DESC,
			'control'=>new texteditorcontrol('',15,50)
		),
		'SITE_403_REAL_HTML'=>array(
			'title'=>TR_CONFIG_SITE_403,
			'description'=>TR_CONFIG_SITE_403_DESC,
			'control'=>new texteditorcontrol('',15,50)
		),
		'SITE_DEFAULT_SECTION'=>array(
			'title'=>TR_CONFIG_SITE_DEFAULT_SECTION,
			'description'=>TR_CONFIG_SITE_DEFAULT_SECTION_DESC,
			'control'=>new dropdowncontrol('',navigationmodule::hierarchyDropDownControlArray())
		),
		'SESSION_TIMEOUT'=>array(
			'title'=>TR_CONFIG_SITE_SESSION_TIMEOUT,
			'description'=>TR_CONFIG_SITE_SESSION_TIMEOUT_DESC,
			'control'=>new textcontrol()
		),
		'SESSION_TIMEOUT_HTML'=>array(
			'title'=>TR_CONFIG_SITE_TIMEOUT_ERROR,
			'description'=>TR_CONFIG_SITE_TIMEOUT_ERROR_DESC,
			'control'=>new texteditorcontrol('',15,50)
		),
		'FILE_DEFAULT_MODE_STR'=>array(
			'title'=>TR_CONFIG_SITE_FILEPERMS,
			'description'=>TR_CONFIG_SITE_FILEPERMS_DESC,
			'control'=>new dropdowncontrol(null,pathos_config_dropdownData('file_permissions'))
		),
		'DIR_DEFAULT_MODE_STR'=>array(
			'title'=>TR_CONFIG_SITE_DIRPERMS,
			'description'=>TR_CONFIG_SITE_DIRPERMS_DESC,
			'control'=>new dropdowncontrol(null,pathos_config_dropdownData('dir_permissions'))
		),
		'ENABLE_SSL'=>array(
			'title'=>TR_CONFIG_SITE_ENABLE_SSL,
			'description'=>TR_CONFIG_SITE_ENABLE_SSL_DESC,
			'control'=>new checkboxcontrol()
		),
		'NONSSL_URL'=>array(
			'title'=>TR_CONFIG_SITE_NONSSL_URL,
			'description'=>TR_CONFIG_SITE_NONSSL_URL_DESC,
			'control'=>new textcontrol()
		),
		'SSL_URL'=>array(
			'title'=>TR_CONFIG_SITE_SSL_URL,
			'description'=>TR_CONFIG_SITE_SSL_URL_DESC,
			'control'=>new textcontrol()
		),
		'WORKFLOW_REVISION_LIMIT'=>array(
			'title'=>'Revision History Limit',
			'description'=>'The maximum number of major revisions (excluding the "current" revision) to keep per item of content.  A limit of 0 (zero) means that all revisions will be kept.',
			'control'=>new textcontrol()
		),
	)
);

$info = gd_info();
if (!PATHOS_HAS_GD) {
	$stuff[1]['SITE_USE_CAPTCHA']['description'] = TR_CONFIG_SITE_USE_CAPTCHA_DESC.'<br /><br />'.TR_CONFIG_SITE_USE_CAPTCHA_NOSUPPORT;
	$stuff[1]['SITE_USE_CAPTCHA']['control']->disabled = true;
}

return $stuff;

?>