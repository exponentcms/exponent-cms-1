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

/**
 * Show a Form for editting or creating contacts.
 *
 * @author James Hunt
 * @copyright 2004 James Hunt and the OIC Group, Inc.
 *
 * @package Modules
 * @subpackage ContactForm
 */
 
if (!defined("PATHOS")) exit("");

$contact = null;
if (isset($_GET['id'])) {
	$contact = $db->selectObject("contact_contact","id=".$_GET['id']);
	if ($contact) $loc = unserialize($contact->location_data);
}

if (pathos_permissions_check("configure",$loc)) {
	$form = contact_contact::form($contact);
	$form->location($loc);
	$form->meta("action","save_contact");
	
	$template = new template("contactmodule","_form_edit_contact",$loc);
	$template->assign("form_html",$form->toHTML());
	$template->assign("is_edit",isset($_GET['id']));
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>