<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# Exponent is distributed in the hope that it
# will be useful, but WITHOUT ANY WARRANTY;
# without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR
# PURPOSE.  See the GNU General Public License
# for more details.
#
# You should have received a copy of the GNU
# General Public License along with Exponent; if
# not, write to:
#
# Free Software Foundation, Inc.,
# 59 Temple Place,
# Suite 330,
# Boston, MA 02111-1307  USA
#
# $Id$
##################################################

// Part of the HTMLArea category

if (!defined('PATHOS')) exit('');

if (pathos_permissions_check('htmlarea',pathos_core_makeLocation('administrationmodule'))) {
	pathos_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);

	$template = new Template('administrationmodule','_htmlareaconfigs',$loc);
	$configs = $db->selectObjects('htmlareatoolbar');
	$template->assign('configs',$configs);
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>