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

// Part of the Configuration category

if (!defined('PATHOS')) exit('');

if (pathos_permissions_check('configuration',pathos_core_makeLocation('administrationmodule'))) {
	pathos_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);
	
	pathos_lang_loadDictionary('modules','administrationmodule');

	$configname = (isset($_GET['configname']) ? $_GET['configname'] : "");
	
	if (!defined('SYS_CONFIG')) include_once(BASE.'subsystems/config.php');
	if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
	pathos_forms_initialize();
	
	$profiles = pathos_config_profiles();
	if (count($profiles) == 0) $profiles = array(''=>'[No Profiles]');
	if (!array_key_exists($configname,$profiles) || $configname == '') {
		if (defined('CURRENTCONFIGNAME')) $configname = CURRENTCONFIGNAME;
		else {
			$keys = array_keys($profiles);
			$configname = $keys[1];
		}
	}
	if (!array_key_exists($configname,$profiles)) $configname = "";
	uasort($profiles,'strnatcmp');
	
	$template = new template('administrationmodule','_configuresiteview',$loc);
	
	$form = new form();
	
	$dd = new dropdowncontrol($configname,$profiles);
	$href = preg_replace("/&configname.*/",'',$_SERVER['REQUEST_URI']);
	$dd->jsHooks['onChange'] = "document.location.href = makeLink('module','administrationmodule','action','configuresite','configname',this.options[this.selectedIndex].value);";
	$form->register('configname',TR_ADMINISTRATIONMODULE_PROFILE,$dd);
	$template->assign('form_html',$form->toHTML());
	
	$template = pathos_config_outputConfigurationTemplate($template,$configname);
	$template->assign('configname',$configname);
	
	$canactivate = ($configname != '' && is_readable(BASE."conf/profiles/$configname.php"));
	$candelete = ($configname != '' && is_really_writable(BASE.'conf/profiles'));
	$canedit = (($configname == '' && (is_really_writable(BASE.'conf/config.php'))) || is_really_writable(BASE.'conf/profiles/'));
	
	$template->assign('canactivate',$canactivate);
	$template->assign('canedit',$canedit);
	$template->assign('candelete',$candelete);
	
	$template->output();
	
	pathos_forms_cleanup();
} else {
	echo SITE_403_HTML;
}

?>