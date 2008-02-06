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

// Part of the Extensions category

if (!defined('EXPONENT')) exit('');

if (exponent_permissions_check('extensions',exponent_core_makeLocation('administrationmodule'))) {
	exponent_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);
	
	$bots = $db->selectObjects('bots');
	$template = new template('bots','_bot_manager',$loc);
	$template->assign('bots', $bots);
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
