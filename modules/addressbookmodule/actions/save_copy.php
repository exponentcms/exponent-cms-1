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


$contact = $db->selectObject("addressbook_contact","id=".$_POST['contact_id']);
if ($contact) {
	$loc = unserialize($contact->location_data);
	$iloc = pathos_core_makeLocation($loc->mod,$loc->src,$contact->id);
	$dloc = pathos_core_makeLocation("addressbookmodule",$_POST['destsrc']);
	
	if ((pathos_permissions_check("copy",$loc) || pathos_permissions_check("copy",$iloc)) &&
		pathos_permissions_check("post",$dloc)
	) {
		unset($contact->id);
		$contact->location_data = serialize($dloc);
		$db->insertobject($contact,"addressbook_contact");
		pathos_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>