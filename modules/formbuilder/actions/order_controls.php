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

$a = $db->selectObject("formbuilder_control","form_id=".$_GET['p']." AND rank=".$_GET['a']);
$b = $db->selectObject("formbuilder_control","form_id=".$_GET['p']." AND rank=".$_GET['b']);
if ($a && $b) {
	$f = $db->selectObject("formbuilder_form","id=".$a->form_id);
	if (pathos_permissions_check("editform",unserialize($f->location_data))) {
		$tmp = $a->rank;
		$a->rank = $b->rank;
		$b->rank = $tmp;
		
		$db->updateObject($a,"formbuilder_control");
		$db->updateObject($b,"formbuilder_control");
		
		pathos_flow_redirect();
	} else echo SITE_403_HTML;
} else echo SITE_404_HTML;

?>