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

$item = null;
$iloc = null;
if (isset($_GET['id'])) {
	$item = $db->selectObject("calendar","id=" . $_GET['id']);	
	if ($item) {
		if (isset($_GET['date_id'])) $item->eventdate = $db->selectObject("eventdate","id=".$_GET['date_id']);
		else $item->eventdate = $db->selectObject("eventdate","event_id=".$item->id);
		$item->eventstart += $item->eventdate->date;
		$item->eventend += $item->eventdate->date;
		$loc = unserialize($item->location_data);
		$iloc = pathos_core_makeLocation($loc->mod,$loc->src,$item->id);
	}
}

if (($item == null && pathos_permissions_check("post",$loc)) ||
	($item != null && pathos_permissions_check("edit",$loc)) ||
	($iloc != null && pathos_permissions_check("edit",$iloc))
) {
	$form = calendar::form($item);
	$form->meta("action","save");
	$form->location($loc);
	
	$config = $db->selectObject("calendarmodule_config","location_data='".serialize($loc)."'");
	if (!$config) {
		$config->enable_categories = 0;
	}
	
	if ($config->enable_categories) {
		$ddopts = array();
		foreach ($db->selectObjects("category","location_data='".serialize($loc)."'") as $opt) {
			$ddopts[$opt->id] = $opt->name;
		}
		uasort($ddopts,"strnatcmp");
		$form->registerBefore("submit","category","Category",new dropdowncontrol($item->category_id,$ddopts));
	}
	
	if (!defined("SYS_MODULES")) include_once(BASE."subsystems/modules.php");
	$form->validationScript = pathos_modules_getJSValidationFile("calendarmodule","postedit");
	
	$template = new template("calendarmodule","_form_edit",$loc);
	$template->assign("form_html",$form->toHTML());
	$template->assign("is_edit",($item == null ? 0 : 1));
	
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>