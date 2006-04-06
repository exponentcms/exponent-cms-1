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
$iloc = null;
if (isset($_GET['id'])) {
	$contact = $db->selectObject('addressbook_contact','id='.intval($_GET['id']));
	if ($contact) {
		$loc = unserialize($contact->location_data);
		$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$contact->id);
	}
}

// FIXME: Replace with better use of getLocationHierarchy
if (($contact == null && exponent_permissions_check('post',$loc)) ||
	($contact != null && exponent_permissions_check('edit',$loc)) ||
	($iloc != null && exponent_permissions_check('edit',$iloc))
) {
	$form = addressbook_contact::form($contact);
	$form->location($loc);
	$form->meta('action','save');
	
	$template = new template('addressbookmodule','_form_edit',$loc);
	$template->assign('form_html',$form->toHTML());
	$template->assign('is_edit',($contact == null ? 0 : 1));
	
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>