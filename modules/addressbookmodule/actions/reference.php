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
//GREP:HARDCODEDTEXT
if (!defined("PATHOS")) exit("");

// PERM CHECK
	if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
	pathos_forms_initialize();
	
	$form = new form();
	$othersources = array();
	foreach ($db->selectObjects("locationref","module='addressbookmodule'") as $l) {
		if ($l->source != $loc->src && substr($l->source,0,7) != "@random") {
			$dloc = pathos_core_makeLocation($l->module,$l->source);
			if (pathos_permissions_check("post",$dloc)) {
				$othersources[$l->source] = pathos_core_translateLocationSource($l->source);
			}
		}
	}
	
	if (count($othersources)) {
		$form->register("destsrc","Destination",new dropdowncontrol(null,$othersources));
		$form->register("submit","",new buttongroupcontrol("Reference","","Cancel"));
		$form->location($loc);
		$form->meta("contact_id",$_GET['id']);
		$form->meta("action","save_reference");
		
		$template = new template("addressbookmodule","_form_reference",$loc);
		$template->assign("form_html",$form->toHTML());
		$template->assign("copy_link","?module=addressbookmodule&src=".$loc->src."&int=".$loc->int."&action=copy&id=".$_GET['id']); // REMOVELINK
		$template->output();
	} else {
		echo "Since you do not have permissions to create new contacts in any of the existing address books on this site, you will not be able to make a reference to this contact.";
	}
	
	pathos_forms_cleanup();
// END PERM CHECK

?>