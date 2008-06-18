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

//exponent_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_ACTION);
if (exponent_users_isLoggedIn()) {
	$config = $db->selectObject('resourcesmodule_config', "location_data='".serialize($loc)."'");
	$template = new template('resourcesmodule','_viewagreement',$loc);            
	$template->assign('user',$user);
	$template->assign('id',$_REQUEST['id']);
	$template->assign('config', $config);
	$template->output();
} else {
	$msg = exponent_lang_getText("A signed confidentiality agreement is required to view this document. To sign the agreement you must first be logged in.");

	flash ('error', $msg);
	exponent_flow_redirect();
}
            

?>
