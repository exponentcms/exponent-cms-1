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

if (!defined('EXPONENT')) exit('');
global $db;

$wizard = null;
if (isset($_POST['id'])) {
	$wizard = $db->selectObject('wizard','id='.intval($_POST['id']));
} 

$wizard = wizard::update($_POST,$wizard);
if (isset($wizard->id)) {
	$db->updateObject($wizard, 'wizard');
} else {
	$id = $db->insertObject($wizard, 'wizard');
}

exponent_flow_redirect();

?>
