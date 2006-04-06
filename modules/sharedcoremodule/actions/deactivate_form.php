<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
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

if (exponent_permissions_check('manage_site',exponent_core_makeLocation('sharedcoremodule'))) {
	$site = null;
	if (isset($_GET['id'])) {
		$site = $db->selectObject('sharedcore_site','id='.intval($_GET['id']));
	}
	
	if ($site) {
		$i18n = exponent_lang_loadFile('modules/sharedcoremodule/actions/deactivate_form.php');
		
		if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		$form = new form();
		$form->meta('module','sharedcoremodule');
		$form->meta('action','deactivate_site');
		
		$dh = opendir(BASE.'modules/sharedcoremodule/views');
		$tpls = array();
		while (($file = readdir($dh)) !== false) {
			if (substr($file,0,8) == '_reason_') $tpls[substr($file,0,-4)] = substr($file,8,-4);
		}
		uksort($tpls,'strnatcmp');
		
		$form->meta('site_id',$site->id);
		$form->register('tpl',$i18n['template'],new dropdowncontrol('',$tpls));
		$form->register('reason',$i18n['reason'],new htmleditorcontrol());
		$form->register('submit','',new buttongroupcontrol($i18n['deactivate'],'',$i18n['cancel']));
	
		$template = new template('sharedcoremodule','_form_deactivate');
		$template->assign('form_html',$form->toHTML());
		$template->output();
	} else {
		echo SITE_404_HTML;
	}
} else {
	echo SITE_403_HTML;
}

?>