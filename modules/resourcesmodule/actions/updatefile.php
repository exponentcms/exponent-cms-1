<?php

##################################################
#
# Copyright 2004 James Hunt and OIC Group, Inc.
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

if (!defined("PATHOS")) exit("");

$item = $db->selectObject("resourceitem","id=".$_GET['id']);
if ($item) {
	$loc = unserialize($item->location_data);
	$iloc = pathos_core_makeLocation($loc->mod,$loc->src,$item->id);
	
	if ((pathos_permissions_check("edit",$loc) || pathos_permissions_check("edit",$iloc)) &&
		($item->flock_owner == 0 || $item->flock_owner == $user->id || $user->is_admin)
	) {
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
		
		$form = new form();
		$form->meta("action","saveupdatedfile");
		$form->location($loc);
		$form->meta("id",$item->id);
		$form->register("file","File",new uploadcontrol());
		if ($item->flock_owner != 0 && ($user->is_admin || $user->id == $item->flock_owner)) {
			$form->register("checkin","Unlock File?",new checkboxcontrol(false,true));
		}
		$form->register("submit","",new buttongroupcontrol("Save","","Cancel"));
		
		$template = new template("resourcesmodule","_form_checkIn",$loc);
		$template->assign("form_html",$form->toHTML());
		$template->assign("resource",$item);
		
		$template->output();
		
		pathos_forms_cleanup();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>