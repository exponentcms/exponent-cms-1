<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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
 
if (!defined("EXPONENT")) exit("");

$item = $db->selectObject("calendar","id=" . intval($_GET['id']));
if ($item) {
	$loc = unserialize($item->location_data);
	$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$item->id);
	$item->permissions = array(
		"edit"=>(exponent_permissions_check("edit",$iloc) || exponent_permissions_check("edit",$loc)),
		"delete"=>(exponent_permissions_check("delete",$iloc) || exponent_permissions_check("delete",$loc)),
		"administrate"=>(exponent_permissions_check("administrate",$iloc) || exponent_permissions_check("administrate",$loc)),
	);
	// Debugger test
	$item->permissions = array(
		"edit"=>exponent_permissions_check("edit",$iloc),
		"delete"=>exponent_permissions_check("delete",$iloc),
		"administrate"=>exponent_permissions_check("administrate",$iloc)
	);
	//FJD - need to check here for if locale is DST and if so, is date DST day, and if so, maybe check for time being after 2AM and then adjust accordingly up or down to correct output.
	
	$eventdate = $db->selectObject("eventdate","id=".intval($_GET['date_id']));
	$item->eventstart += $eventdate->date;
	$item->eventend += $eventdate->date;
	$item->eventdate = $eventdate;
	
	$template = new template("calendarmodule","_view",$loc);				
	
	if ($item->feedback_form != "") {
		$formtemplate = new formtemplate('email', $item->feedback_form);
		$formtemplate->assign('formname', $item->feedback_form);
		$formtemplate->assign('module','calendarmodule');
		$formtemplate->assign('loc', $loc);
		$formtemplate->assign('action', 'send_feedback');
		$formtemplate->assign('id', $item->id);
		$form = $formtemplate->render();			
		$template->assign('form', $form);
	} 
	
	eDebug($item);
	$template->assign("item",$item);
	$template->assign("directory","files/calendarmodule/".$loc->src);
	$template->register_permissions(
		array("post","edit","delete","administrate","manage_approval"),
		$loc
	);
	
	$template->output();
} else {
	echo SITE_404_HTML;
}

?>
