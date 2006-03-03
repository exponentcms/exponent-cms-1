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

if (!defined('EXPONENT')) exit('');

$loc = exponent_core_makeLocation('sharedcoremodule');
if (exponent_permissions_check('manage_core',$loc) || exponent_permissions_check('manage_site',$loc)) {
	$site = null;
	if (isset($_GET['id'])) {
		$site = $db->selectObject('sharedcore_site','id='.intval($_GET['id']));
	}
	
	if ($site) {
		if ($site->inactive == 0) {
			$core = $db->selectObject('sharedcore_core','id='.$site->core_id);
			if ($core) {
				if (!defined('SYS_SHAREDCORE')) include_once(BASE.'subsystems/sharedcore.php');
				
				exponent_sharedcore_clear($site->path); // Not full
				
				exponent_sharedcore_setup($core,$site);
				
				$extensions = array(
					CORE_EXT_MODULE=>array(),
					CORE_EXT_SUBSYSTEM=>array(),
					CORE_EXT_THEME=>array(),
				);
				
				foreach ($db->selectObjects('sharedcore_extension','site_id='.$site->id) as $e) {
					$extensions[$e->type][] = $e->name;
				}
				
				exponent_sharedcore_link($core,$site,$extensions);
				
				exponent_flow_redirect();
			} else {
				echo SITE_404_HTML; // temp
			}
		} else {
			echo SITE_403_HTML;
		}
	} else {
		echo SITE_404_HTML;
	}
} else {
	echo SITE_403_HTML;
}

?>