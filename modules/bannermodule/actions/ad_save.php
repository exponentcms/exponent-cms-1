<?php

##################################################
# __XYZZY_BOILER
# $Id$
##################################################

if (!defined("PATHOS")) exit("");

$banner = null;
if (isset($_POST['id'])) {
	$banner = $db->selectObject("banner_ad","id=".$_POST['id']);
	$loc = unserialize($banner->location_data);
}

if (pathos_permissions_check("manage",$loc)) {
	
	$banner = banner_ad::update($_POST,$banner);
	$banner->location_data = serialize($loc);
	
	if (!isset($banner->file_id)) {
		$directory = "files/bannermodule/".$loc->src;
		$file = file::update("file",$directory,null);
		if ($file != null) {
			$banner->file_id = $db->insertObject($file,"file");
			$db->insertObject($banner,"banner_ad");
		}
	} else {
		$db->updateObject($banner,"banner_ad");
	}
	pathos_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>