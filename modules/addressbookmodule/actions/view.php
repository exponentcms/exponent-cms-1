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

$contact = null;
if (isset($_GET['id'])) {
	$contact = $db->selectObject('addressbook_contact','id='.intval($_GET['id']));
}

if ($contact) {
	exponent_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_ACTION);
	
	$loc = unserialize($contact->location_data);

	$template = new template('addressbookmodule','_view',$loc);
	$template->assign('contact',$contact);
	$template->register_permissions(
		array('edit','delete'),
		exponent_core_makeLocation($loc->mod,$loc->src,$contact->id)
	);
	
	$template->output();
} else {
	echo SITE_404_HTML;
}

?>