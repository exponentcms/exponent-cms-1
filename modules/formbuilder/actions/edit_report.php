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

$f = null;
$rept = null;
if (isset($_GET['id'])) $f = $db->selectObject("formbuilder_form","id=".$_GET['id']);
if ($f) {
	if (pathos_permissions_check("editreport",unserialize($f->location_data))) {
		$floc = unserialize($f->location_data);
		$rept = $db->selectObject("formbuilder_report","form_id=".$f->id);
	
	
	
		$form = formbuilder_report::form($rept);
		$form->location($loc);
		$form->meta("action","save_report");
		$form->meta("id",$rept->id);
		$form->meta("m",$floc->mod);
		$form->meta("s",$floc->src);
		$form->meta("i",$floc->int);
		echo $form->toHTML();
	} else echo SITE_403_HTML;
} else echo SITE_404_HTML;



?>