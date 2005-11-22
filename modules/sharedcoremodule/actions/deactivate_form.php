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

if (pathos_permissions_check('manage_site',pathos_core_makeLocation('sharedcoremodule'))) {
	$site = null;
	if (isset($_GET['id'])) {
		$site = $db->selectObject('sharedcore_site','id='.intval($_GET['id']));
	}
	
	if ($site) {
		$i18n = pathos_lang_loadFile('modules/sharedcoremodule/actions/deactivate_form.php');
		
		if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
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