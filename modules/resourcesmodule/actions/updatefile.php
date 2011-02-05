<?php

##################################################
#
# Copyright (c) 2004-2011 OIC Group, Inc.
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

$item = $db->selectObject('resourceitem','id='.intval($_GET['id']));
if ($item) {
	$loc = unserialize($item->location_data);
	$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$item->id);
	
	if ((exponent_permissions_check('edit',$loc) || exponent_permissions_check('edit',$iloc)) &&
		($item->flock_owner == 0 || $item->flock_owner == $user->id || $user->is_acting_admin == 1)
	) {
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		exponent_sessions_clearAllUsersSessionCache('resourcesmodule');
		
		$i18n = exponent_lang_loadFile('modules/resourcesmodule/actions/updatefile.php');
		
		$form = new form();
		$form->meta('action','saveupdatedfile');
		$form->location($loc);
		$form->meta('id',$item->id);
		$form->register('file',$i18n['file'],new uploadcontrol());
		if ($item->flock_owner != 0 && ($user->is_acting_admin == 1 || $user->id == $item->flock_owner)) {
			$form->register('checkin',$i18n['unlock'],new checkboxcontrol(false,true));
		}
		$form->register('fileexists', '(or) Select an Existing File', new customcontrol("<input class=\"kfm\" id=\"fileexists\" type=\"text\" name=\"fileexists\" size=\"80\" maxlength=\"200\">"));	
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		$template = new template('resourcesmodule','_form_checkIn',$loc);
		$template->assign('form_html',$form->toHTML());
		$template->assign('resource',$item);
		
		$template->output();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>
