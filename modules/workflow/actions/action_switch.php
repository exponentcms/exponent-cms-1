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

if (!defined('PATHOS')) exit('');

if (pathos_permissions_check('workflow',pathos_core_makeLocation('administrationmodule'))) {
	$db->switchValues('workflowaction','rank',intval($_GET['a']),intval($_GET['b']),"policy_id='".intval($_GET['policy_id'])."' AND type=".$_GET['type']);
	pathos_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>