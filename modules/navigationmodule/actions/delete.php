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

if ($user->is_admin == 1) {
	function tmp_deleteLevel($parent) {
		global $db;
		$kids = $db->selectObjects("section","parent=$parent");
		foreach ($kids as $kid) {
			tmp_deleteLevel($kid->id);
		}
		$secrefs = $db->selectObjects("sectionref","section=".$parent);
		foreach ($secrefs as $secref) {
			$loc = pathos_core_makeLocation($secref->module,$secref->source,$secref->internal);
			pathos_core_decrementLocationReference($loc,$parent);
			
			foreach ($db->selectObjects("locationref","module='".$secref->module."' AND source='".$secref->source."' AND internal='".$secref->internal."' AND refcount = 0") as $locref) {
				$modclass = $locref->module;
				$mod = new $modclass();
				$mod->deleteIn(pathos_core_makeLocation($locref->module,$locref->source,$locref->internal));
			}
			$db->delete("locationref","module='".$secref->module."' AND source='".$secref->source."' AND internal='".$secref->internal."' AND refcount = 0");
		}
		$db->delete("sectionref","section=".$parent);
		
		$db->delete("section","parent=$parent");
	}
	
	tmp_deleteLevel($_GET['id']);
	$db->delete("section","id=" . $_GET['id']);
	
	pathos_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>