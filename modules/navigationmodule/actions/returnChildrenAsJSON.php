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
	//$nav = navigationmodule::levelTemplate(intval($_REQUEST['id'], 0));
	$nav = $db->selectObjects('section', 'parent='.intval($_REQUEST['id']), 'rank');
	
	$manage_all = false;
	if (exponent_permissions_check('manage',exponent_core_makeLocation('navigationmodule','',intval($_REQUEST['id'])))) {$manage_all = true;}

	for($i=0; $i<count($nav);$i++) {
		if ($manage_all || exponent_permissions_check('manage',exponent_core_makeLocation('navigationmodule','',$nav[$i]->id))) {
			$nav[$i]->manage = 1;
		} else {
			$nav[$i]->manage = 0;
		}
	}
	echo exponent_javascript_ajaxReply(201, '', $nav);
?>
