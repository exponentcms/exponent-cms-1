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
 * View List of Module Types that have Archived Modules
 *
 * @author James Hunt
 * @copyright 2004 James Hunt and the OIC Group, Inc.
 *
 * @package Modules
 * @subpackage Container
 */

if (!defined("PATHOS")) exit("");

// PERM CHECK
	$orphan_mods = array();
	foreach ($db->selectObjects("locationref","refcount = 0") as $orphan) {
		if (!isset($orphan_mods[$orphan->module]) && class_exists($orphan->module)) {
			$modclass = $orphan->module;
			$mod = new $modclass();
			$orphan_mods[$modclass] = $mod->name();
		}
	}
	uasort($orphan_mods,"strnatcmp");
	
	$template = new template("containermodule","_orphans_modules");
	$template->assign("orphan_mods",$orphan_mods);
	$template->output();
// END PERM CHECK

?>