<?php

##################################################
# __XYZZY_BOILER
# $Id$
##################################################

if (!defined("PATHOS")) exit("");

if (pathos_permissions_check("manage_af",$loc)) {
	$af = $db->selectObject("banner_affiliate","id=".$_GET['id']);
	if ($af) {
		$db->delete("banner_affiliate","id=".$_GET['id']);
		pathos_flow_redirect();
	} else {
		echo SITE_404_HTML;
	}
} else {
	echo SITE_403_HTML;
}

?>