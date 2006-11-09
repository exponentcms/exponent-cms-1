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

$tag = null;
//if (isset($_POST['id'])) $collection = $db->selectObject("tags","id=".intval($_POST['id']));
if (exponent_permissions_check('extensions',exponent_core_makeLocation('administrationmodule'))) {
	$tag->name = $_POST['name'];
	$tag->collection_id = $_POST['collection_id'];
	$db->insertObject($tag,"tags");
	exponent_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>
