<?php

##################################################
# __XYZZY_BOILER
# $Id$
##################################################

if (!defined("PATHOS")) exit("");

if (pathos_permissions_check("manage_af",$loc)) {
	$af = null;
	if (isset($_POST['id'])) {
		$af = $db->selectObject("banner_affiliate","id=".$_POST['id']);
	}
	
	$af = banner_affiliate::update($_POST,$af);
	
	if (isset($af->id)) {
		$db->updateObject($af,"banner_affiliate");
	} else {
		$db->insertObject($af,"banner_affiliate");
	}
	pathos_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>