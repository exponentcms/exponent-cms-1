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

if (!defined("PATHOS")) exit("");

if (pathos_permissions_check('manage_site',pathos_core_makeLocation('sharedcoremodule'))) {
	$site = null;
	$core = null;
	if (isset($_POST['site_id'])) {
		$site = $db->selectObject("sharedcore_site","id=".intval($_POST['site_id']));
		if ($site) {
			$core = $db->selectObject("sharedcore_core","id=".$site->core_id);
		}
	}
	
	if ($site && $core) {
		if ($site->inactive == 0) {
			
			// Remove previous 'unfrozen' extensions
			$db->delete("sharedcore_extension","site_id=".$site->id." AND locked = 0");
			
			if (!defined("SYS_SHAREDCORE")) include_once(BASE."subsystems/sharedcore.php");
			
			// Need to clear the old path.
			pathos_sharedcore_clear($site->path); // Do not do a full delete
			// Relink the core to the linked site
			pathos_sharedcore_setup($core,$site);
			
			$used = array(
				CORE_EXT_MODULE=>array(),
				CORE_EXT_SUBSYSTEM=>array(),
				CORE_EXT_THEME=>array(),
			);
			
			foreach ($db->selectObjects('sharedcore_extension','site_id='.$site->id) as $e) {
				$used[$e->type][] = $e->name;
			}
			
			//Process all modules and themes
			$extension = null;
			$extension->site_id = $site->id;
			$extension->locked = 0;
			$extension->type = CORE_EXT_MODULE;
			if (isset($_POST['mods'])) {
				foreach (array_keys($_POST['mods']) as $mod) {
					$extension->name = $mod;
					$used[CORE_EXT_MODULE][] = $mod;
					$db->insertObject($extension,"sharedcore_extension");
				}
			}
			
			$extension->type = CORE_EXT_THEME;
			if (isset($_POST['themes'])) {
				foreach (array_keys($_POST['themes']) as $theme) {
					$extension->name = $theme;
					$used[CORE_EXT_THEME][] = $theme;
					$db->insertObject($extension,"sharedcore_extension");
				}
			}
			
			pathos_sharedcore_link($core,$site,$used);
			
			pathos_flow_redirect();
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