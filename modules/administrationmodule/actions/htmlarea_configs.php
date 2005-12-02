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

if (!defined('PATHOS')) exit('');

if (pathos_permissions_check('htmlarea',pathos_core_makeLocation('administrationmodule'))) {
	pathos_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);

	$template = new template('administrationmodule','_htmlareaconfigs',$loc);
	$configs = $db->selectObjects('htmlareatoolbar');
	$template->assign('configs',$configs);
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>