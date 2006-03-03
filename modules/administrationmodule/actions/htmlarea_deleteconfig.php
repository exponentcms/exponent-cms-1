<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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

// Part of the HTMLArea category

if (!defined('EXPONENT')) exit('');

if (isset($_GET['id']) && exponent_permissions_check('htmlarea',exponent_core_makeLocation('administrationmodule'))) {
	$db->delete('htmlareatoolbar','id='.intval($_GET['id']));
	exponent_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>