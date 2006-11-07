<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Copyright (c) 2006 Maxim Mueller
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

if (!defined('EXPONENT')) exit('');

$i18n = exponent_lang_loadFile('conf/extensions/site.structure.php');

foreach (glob(BASE . "external/editors/*.glue") as $installed_glue_file) {
   $installed_editor = basename($installed_glue_file, ".glue");
   //also check if the editor is actually installed, not just its glue file
   if (file_exists(substr($installed_glue_file, 0, -5))) { 
		$installed_editors[$installed_editor] = $installed_editor;
   }
}

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
			'control'=>new dropdowncontrol(0,exponent_lang_list())
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
		'SITE_WYSIWYG_EDITOR'=>array(
			'title'=>$i18n['wysiwyg_editor'],
			'description'=>$i18n['wysiwyg_editor_desc'],
			'control'=>new dropdowncontrol(null,$installed_editors)
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
			'control'=>new dropdowncontrol(null,exponent_config_dropdownData('file_permissions'))
		),
		'DIR_DEFAULT_MODE_STR'=>array(
			'title'=>$i18n['dirperms'],
			'description'=>$i18n['dirperms_desc'],
			'control'=>new dropdowncontrol(null,exponent_config_dropdownData('dir_permissions'))
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
		'ENABLE_WORKFLOW'=>array(
			'title'=>$i18n['enable_workflow'],
			'description'=>$i18n['enable_workflow_desc'],
			'control'=>new checkboxcontrol()
		),
		'WORKFLOW_REVISION_LIMIT'=>array(
			'title'=>$i18n['revision_limit'],
			'description'=>$i18n['revision_limit_desc'],
			'control'=>new textcontrol()
		),
	)
);

$info = gd_info();
if (!EXPONENT_HAS_GD) {
	$stuff[1]['SITE_USE_CAPTCHA']['description'] = $i18n['use_captcha_desc'].'<br /><br />'.$i18n['no_gd_support'];
	$stuff[1]['SITE_USE_CAPTCHA']['control']->disabled = true;
}

return $stuff;

?>
