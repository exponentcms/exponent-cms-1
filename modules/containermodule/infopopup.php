<?php
exit('This feature has been deprecated');

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

define("SCRIPT_EXP_RELATIVE","modules/containermodule/");
define("SCRIPT_FILENAME","infopopup.php");

include("../../pathos.php");

$template = new template("containermodule","_popup_info");
$locref = null;

if (isset($_GET['id'])) {
	$container = $db->selectObject("container","id=".intval($_GET['id']));
	if ($container) {
		$iloc = unserialize($container->internal);
		$locref = $db->selectObject("locationref","module='".$iloc->mod."' AND source='".$iloc->src."'");
	
		$template->assign("is_orphan",0);
		$template->assign("container",$container);
	} else {
		exit(''.SITE_404_HTML);
	}
} else {
	// GREP:SECURITY -- SQL created from _GET parameter that is non numeric.  Needs to be sanitized.
	$locref = $db->selectObject("locationref","module='".$_GET['mod']."' AND source='".$_GET['src']."'");
	$template->assign("is_orphan",1);
}

if ($locref) {
	if (class_exists($locref->module)) $template->assign("name",call_user_func(array($locref->module,"name")));
	else $template->assign("name","");
	
	$template->assign("info",$locref->description);
} else {
	$template->assign("name","");
	$template->assign("info","");
}

$template->output();

?>