<?php

##################################################
# __XYZZY_BOILER
# $Id$
##################################################

if (!defined("PATHOS")) exit("");

$banner = null;
if (isset($_GET['id'])) $banner = $db->selectObject("banner_ad","id=".$_GET['id']);

if ($banner) {
	$loc = unserialize($banner->location_data);
	if (pathos_permissions_check("manage",$loc)) {
		$db->delete("banner_ad","id=".$banner->id);
		pathos_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>