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

$item = $db->selectObject('resourceitem','id='.intval($_GET['id']));
if ($item) {
	$loc = unserialize($item->location_data);
	$iloc = pathos_core_makeLocation($loc->mod,$loc->src,$item->id);
	
	if ((pathos_permissions_check('edit',$loc) || pathos_permissions_check('edit',$iloc)) &&
		($item->flock_owner == 0 || $item->flock_owner == $user->id || $user->is_acting_admin == 1)
	) {
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$i18n = pathos_lang_loadFile('modules/resourcesmodule/actions/updatefile.php');
		
		$form = new form();
		$form->meta('action','saveupdatedfile');
		$form->location($loc);
		$form->meta('id',$item->id);
		$form->register('file',$i18n['file'],new uploadcontrol());
		if ($item->flock_owner != 0 && ($user->is_acting_admin == 1 || $user->id == $item->flock_owner)) {
			$form->register('checkin',$i18n['unlock'],new checkboxcontrol(false,true));
		}
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
