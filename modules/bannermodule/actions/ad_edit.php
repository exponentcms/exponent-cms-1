<?php

##################################################
# __XYZZY_BOILER
# $Id$
##################################################

if (!defined("PATHOS")) exit("");

$banner = null;
if (isset($_GET['id'])) {
	$banner = $db->selectObject("banner_ad","id=".$_GET['id']);
	$loc = unserialize($banner->location_data);
}

if (pathos_permissions_check("manage",$loc)) {
	$form = banner_ad::form($banner);
	$form->location($loc);
	$form->meta("action","ad_save");
	
	$template = new template("bannermodule","_form_ad_edit");
	$template->assign("form_html",$form->toHTML());
	$template->assign("is_edit",isset($_GET['id']));
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>