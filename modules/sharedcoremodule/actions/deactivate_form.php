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

if (pathos_permissions_check('manage_site',pathos_core_makeLocation('sharedcoremodule'))) {
	$site = null;
	if (isset($_GET['id'])) {
		$site = $db->selectObject('sharedcore_site','id='.$_GET['id']);
	}
	
	if ($site) {
		pathos_lang_loadDictionary('modules','sharedcoremodule');
		pathos_lang_loadDictionary('standard','core');
		
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
		
		$form->meta('site_id',$_GET['id']);
		$form->register('tpl',TR_SHAREDCOREMODULE_TEMPLATE,new dropdowncontrol('',$tpls));
		$form->register('reason',TR_SHAREDCOREMODULE_REASON,new htmleditorcontrol());
		$form->register('submit','',new buttongroupcontrol(TR_SHAREDCOREMODULE_DEACTIVATE,'',TR_CORE_CANCEL));
	
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