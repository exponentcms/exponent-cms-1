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

$iloc = pathos_core_makeLocation($_GET['m'],@$_GET['s'],@$_GET['i']);

// Make sure that locref refcount is indeed 0.
$locref = $db->selectObject("locationref","module='".$iloc->mod."' AND source='".$iloc->src."' AND internal='".$iloc->int."'");
if ($locref && $locref->refcount == 0 && pathos_permissions_check("administrate",$iloc)) {
	// delete in location.
	$modclass = $iloc->mod;
	$mod = new $modclass();
	$mod->deleteIn($iloc);
	$db->delete("locationref","module='".$iloc->mod."' AND source='".$iloc->src."' AND internal='".$iloc->int."'");
	pathos_permissions_revokeComplete($iloc);
}

pathos_flow_redirect();

?>