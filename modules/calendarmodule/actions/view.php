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
 
if (!defined("PATHOS")) exit("");

$item = $db->selectObject("calendar","id=" . $_GET['id']);
if ($item) {
	$loc = unserialize($item->location_data);
	$iloc = pathos_core_makeLocation($loc->mod,$loc->src,$item->id);
	$item->permissions = array(
		"edit"=>(pathos_permissions_check("edit",$iloc) || pathos_permissions_check("edit",$loc)),
		"delete"=>(pathos_permissions_check("delete",$iloc) || pathos_permissions_check("delete",$loc)),
		"administrate"=>(pathos_permissions_check("administrate",$iloc) || pathos_permissions_check("administrate",$loc)),
	);
	// Debugger test
	$item->permissions = array(
		"edit"=>pathos_permissions_check("edit",$iloc),
		"delete"=>pathos_permissions_check("delete",$iloc),
		"administrate"=>pathos_permissions_check("administrate",$iloc)
	);
	$eventdate = $db->selectObject("eventdate","id=".$_GET['date_id']);
	$item->eventstart += $eventdate->date;
	$item->eventend += $eventdate->date;
	$item->eventdate = $eventdate;
	
	$template = new Template("calendarmodule","_view",$loc);				
	
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