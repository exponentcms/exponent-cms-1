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

// Part of the Administration Control Panel : Files Subsystem category

if (!defined('PATHOS')) exit('');

if (pathos_permissions_check('files_subsystem',pathos_core_makeLocation('administrationmodule'))) {
	$type = null;
	if (isset($_GET['type'])) {
		$type = $db->selectObject('mimetype',"mimetype='".$_GET['type']."'");
	}
	
	if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
	pathos_forms_initialize();
	
	$form = mimetype::form($type);
	$form->meta('module','filemanager');
	$form->meta('action','admin_savemimetype');
	
	$template = new template('filemanager','_form_editmimetype',$loc);
	$template->assign('form_html',$form->toHTML());
	$template->assign('is_edit',isset($type->id)?1:0);
	$template->output();
	
	pathos_forms_cleanup();
} else {
	echo SITE_403_HTML;
}

?>