<?php

##################################################
# __XYZZY_BOILER
# $Id$
##################################################

if (!defined("PATHOS")) exit("");

$af = null;
if (isset($_GET['id'])) {
	$af = $db->selectObject("banner_affiliate","id=".$_GET['id']);
}

if (pathos_permissions_check("manage_af",$loc)) {
	$form = banner_affiliate::form($af);
	$form->meta("module","bannermodule");
	$form->meta("action","af_save");
	
	$template = new template("bannermodule","_form_af_edit",$loc);
	$template->assign("form_html",$form->toHTML());
	$template->assign("is_edit",isset($_GET['id']));
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>