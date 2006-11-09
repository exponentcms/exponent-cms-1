<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
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

$collection = null;
if (isset($_POST['id'])) $collection = $db->selectObject("tag_collections","id=".intval($_POST['id']));
if (exponent_permissions_check('extensions',exponent_core_makeLocation('administrationmodule'))) {
	$collection = tag_collections::update($_POST,$collection);
	if (isset($collection->id)) {
		$db->updateObject($collection,"tag_collections");
	} else {
		$db->insertObject($collection,"tag_collections");
	}
	exponent_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>
