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

if (!defined("PATHOS")) exit("");

// PERM CHECK
	$site = $db->selectObject("sharedcore_site","id=".$_POST['site_id']);
	if ($site) {
		$core = $db->selectObject("sharedcore_core","id=".$site->core_id);
		
		// Remove previous 'unfrozen' extensions
		$db->delete("sharedcore_extension","site_id=".$site->id." AND locked = 0");
		
		if (!defined("SYS_SHAREDCORE")) include_once(BASE."subsystems/sharedcore.php");
		
		// Need to clear the old path.
		pathos_sharedcore_clear($site->path); // Do not do a full delete
		// Relink the core to the linked site
		pathos_sharedcore_linkCore($core->path,$site->path);
		
		//Process all modules and themes
		$extension = null;
		$extension->site_id = $site->id;
		$extension->locked = 0;
		$extension->type = CORE_EXT_MODULE;
		if (isset($_POST['mods'])) {
			foreach (array_keys($_POST['mods']) as $mod) {
				pathos_sharedcore_linkExtension(CORE_EXT_MODULE,$mod,$core->path,$site->path);
				$extension->name = $mod;
				$db->insertObject($extension,"sharedcore_extension");
			}
		}
		
		$extension->type = CORE_EXT_THEME;
		if (isset($_POST['themes'])) {
			foreach (array_keys($_POST['themes']) as $theme) {
				pathos_sharedcore_linkExtension(CORE_EXT_THEME,$theme,$core->path,$site->path);
				$extension->name = $theme;
				$db->insertObject($extension,"sharedcore_extension");
			}
		}
		pathos_flow_redirect();
	} else echo SITE_404_HTML;
// END PERM CHECK

?>