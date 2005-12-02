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

if (pathos_permissions_check('manage_core',pathos_core_makeLocation('sharedcoremodule'))) {
	$core = null;
	if (isset($_GET['id'])) {
		$core = $db->selectObject('sharedcore_core','id='.intval($_GET['id']));
	}
	
	if ($core) {
		$db->delete('sharedcore_core','id='.$core->id);
		
		if (!defined('SYS_SHAREDCORE')) include_once(BASE.'subsystems/sharedcore.php');
		foreach ($db->selectObjects('sharedcore_site','core_id='.$core->id) as $site) {
			$db->delete('sharedcore_extension','site_id='.$site->id);
			pathos_sharedcore_clear($site->path,true);
		}
		
		$db->delete('sharedcore_site','core_id='.$core->id);
		pathos_flow_redirect();
	} else {
		echo SITE_404_HTML;
	}
} else {
	echo SITE_403_HTML;
}

?>