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
 * Save an HTML Template
 *
 * @author James Hunt
 * @copyright 2004 James Hunt and the OIC Group, Inc.
 *
 * @package Modules
 * @subpackage HTMLTemplate
 */

if (!defined("PATHOS")) exit("");

$t = null;
if (isset($_POST['id'])) $t = $db->selectObject("htmltemplate","id=".$_POST['id']);

if ((!$t && pathos_permissions_check("create",$loc)) ||
	($t  && pathos_permissions_check("edit",$loc))
) {

	$t = htmltemplate::update($_POST,$t);
	if (isset($t->id)) $db->updateObject($t,"htmltemplate");
	else $db->insertObject($t,"htmltemplate");
	pathos_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>