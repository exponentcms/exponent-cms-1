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

$i18n = pathos_lang_loadFile('conf/extensions/site.structure.php');

$stuff = array(
	$i18n['title'],
	array(
		'SITE_TITLE'=>array(
			'title'=>$i18n['site_title'],
			'description'=>$i18n['site_title_desc'],
			'control'=>new textcontrol()
		),
		'USE_LANG'=>array(
			'title'=>$i18n['use_lang'],
			'description'=>$i18n['use_lang_desc'],
			'control'=>new dropdowncontrol(0,pathos_lang_list())
		),
		'SITE_ALLOW_REGISTRATION'=>array(
			'title'=>$i18n['allow_registration'],
			'description'=>$i18n['allow_registration_desc'],
			'control'=>new checkboxcontrol()
		),
		'SITE_USE_CAPTCHA'=>array(
			'title'=>$i18n['use_captcha'],
			'description'=>$i18n['use_captcha_desc'],
			'control'=>new checkboxcontrol()
		),
		'SITE_KEYWORDS'=>array(
			'title'=>$i18n['site_keywords'],
			'description'=>$i18n['site_keywords_desc'],
			'control'=>new texteditorcontrol('',10,30)
		),
		'SITE_DESCRIPTION'=>array(
			'title'=>$i18n['site_description'],
			'description'=>$i18n['site_description_desc'],
			'control'=>new texteditorcontrol('',15,50)
		),
		'SITE_404_HTML'=>array(
			'title'=>$i18n['site_404'],
			'description'=>$i18n['site_404_desc'],
			'control'=>new texteditorcontrol('',15,50)
		),
		'SITE_403_REAL_HTML'=>array(
			'title'=>$i18n['site_403'],
			'description'=>$i18n['site_403_desc'],
			'control'=>new texteditorcontrol('',15,50)
		),
		'SITE_DEFAULT_SECTION'=>array(
			'title'=>$i18n['default_section'],
			'description'=>$i18n['default_section_desc'],
			'control'=>new dropdowncontrol('',navigationmodule::levelDropDownControlArray(0))
		),
		'SESSION_TIMEOUT'=>array(
			'title'=>$i18n['session_timeout'],
			'description'=>$i18n['session_timeout_desc'],
			'control'=>new textcontrol()
		),
		'SESSION_TIMEOUT_HTML'=>array(
			'title'=>$i18n['timeout_error'],
			'description'=>$i18n['timeout_error_desc'],
			'control'=>new texteditorcontrol('',15,50)
		),
		'FILE_DEFAULT_MODE_STR'=>array(
			'title'=>$i18n['fileperms'],
			'description'=>$i18n['fileperms_desc'],
			'control'=>new dropdowncontrol(null,pathos_config_dropdownData('file_permissions'))
		),
		'DIR_DEFAULT_MODE_STR'=>array(
			'title'=>$i18n['dirperms'],
			'description'=>$i18n['dirperms_desc'],
			'control'=>new dropdowncontrol(null,pathos_config_dropdownData('dir_permissions'))
		),
		'ENABLE_SSL'=>array(
			'title'=>$i18n['ssl'],
			'description'=>$i18n['ssl_desc'],
			'control'=>new checkboxcontrol()
		),
		'NONSSL_URL'=>array(
			'title'=>$i18n['nonssl_url'],
			'description'=>$i18n['nonssl_url_desc'],
			'control'=>new textcontrol()
		),
		'SSL_URL'=>array(
			'title'=>$i18n['ssl_url'],
			'description'=>$i18n['ssl_url_desc'],
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
	$stuff[1]['SITE_USE_CAPTCHA']['description'] = $i18n['use_captcha_desc'].'<br /><br />'.$i18n['no_gd_support'];
	$stuff[1]['SITE_USE_CAPTCHA']['control']->disabled = true;
}

return $stuff;

?>