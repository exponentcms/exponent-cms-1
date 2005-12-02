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

if (pathos_permissions_check('manage',pathos_core_makeLocation('navigationmodule','',(int)$_GET['parent']))) {
	$db->switchValues('section','rank',$_GET['a'],$_GET['b'],'parent=' . (int)$_GET['parent']);
	pathos_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>
