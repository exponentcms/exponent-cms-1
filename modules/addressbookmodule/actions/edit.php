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

$contact = null;
$iloc = null;
if (isset($_GET['id'])) {
	$contact = $db->selectObject("addressbook_contact","id=".$_GET['id']);
	$loc = unserialize($contact->location_data);
	$iloc = pathos_core_makeLocation($loc->mod,$loc->src,$contact->id);
}

if (($contact == null && pathos_permissions_check("post",$loc)) ||
	($contact != null && pathos_permissions_check("edit",$loc)) ||
	($iloc != null && pathos_permissions_check("edit",$iloc))
) {
	$form = addressbook_contact::form($contact);
	$form->location($loc);
	$form->meta("action","save");
	
	$template = new template("addressbookmodule","_form_edit",$loc);
	$template->assign("form_html",$form->toHTML());
	$template->assign("is_edit",($contact == null ? 0 : 1));
	
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>