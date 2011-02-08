<?php

##################################################
#
# Copyright (c) 2004-2011 OIC Group, Inc.
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

if (!defined('EXPONENT')) exit('');

$collection = null;
if (!isset($_GET['id'])) {
	exponent_flow_redirect();
}

$existing_tags = array();
$existing_tags = $db->selectObjects('tags', 'collection_id='.$_GET['id']);
if (!defined('SYS_SORTING')) include_once(BASE.'subsystems/sorting.php');
usort($existing_tags, "exponent_sorting_byNameAscending");

if (exponent_permissions_check('extensions',exponent_core_makeLocation('administrationmodule'))) {
	exponent_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);

	$template = new template("tags","_add_tags");
	$template->assign('tag_collection', $_GET['id']);
	$template->assign('existing_tags', $existing_tags);
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
