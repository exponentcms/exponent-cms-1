<?php

##################################################
#
# Copyright 2004 James Hunt and OIC Group, Inc.
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
	$site = $db->selectObject("sharedcore_site","id=".$_GET['id']);
	if ($site) {
		$core = $db->selectObject("sharedcore_core","id=".$site->core_id);
		if ($core) {
			$site->inactive = 0;
			$db->updateObject($site,"sharedcore_site");
			unlink($site->path."index.php");
			
			$extensions = $db->selectObjects("sharedcore_extension","site_id=".$site->id);
			if (!defined("SYS_SHAREDCORE")) include_once(BASE."subsystems/sharedcore.php");
			pathos_sharedcore_linkCore($core->path,$site->path);
			foreach ($extensions as $e) {
				pathos_sharedcore_linkExtension($e->type,$e->name,$core->path,$site->path);
			}
			pathos_flow_redirect();
		} else echo SITE_404_HTML; // temp
	} else echo SITE_404_HTML;
// END PERM CHECK

?>