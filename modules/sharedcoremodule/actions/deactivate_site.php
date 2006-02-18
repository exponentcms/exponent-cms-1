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

if (pathos_permissions_check('manage_site',pathos_core_makeLocation('sharedcoremodule'))) {
	$site = null;
	if (isset($_POST['site_id'])) {
		$site = $db->selectObject('sharedcore_site','id='.intval($_POST['site_id']));
	}
	
	if ($site) {
		if (!defined('SYS_SHAREDCORE')) require_once(BASE.'subsystems/sharedcore.php');
		pathos_sharedcore_clear($site->path); // Do not delete other stuff.
		
		$template = new template('sharedcoremodule',$_POST['tpl']);
		$template->assign('reason',$_POST['reason']);
		
		$fh = fopen($site->path.'index.php','w');
		fwrite($fh,$template->render());
		fclose($fh);
		
		$site->inactive = 1;
		$db->updateObject($site,'sharedcore_site');
		
		pathos_flow_redirect();
	} else {
		echo SITE_404_HTML;
	}
} else {
	echo SITE_403_HTML;
}

?>