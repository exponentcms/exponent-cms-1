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

$contact = null;
if (isset($_GET['id'])) {
	$contact = $db->selectObject('contact_contact','id='.intval($_GET['id']));
	if ($contact) {
		$loc = unserialize($contact->location_data);
	}
}

if (exponent_permissions_check('configure',$loc)) {
	$form = contact_contact::form($contact);
	$form->location($loc);
	$form->meta('action','save_contact');
	
	$template = new template('contactmodule','_form_edit_contact',$loc);
	$template->assign('form_html',$form->toHTML());
	$template->assign('is_edit', (isset($_GET['id']) ? 1 : 0) );
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>