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
 * Sorting Subsystems
 *
 * The sorting subsytem brings together general
 * sorting callbacks for use in other parts of the system.
 *
 *
 * @package		Subsystems
 * @subpackage	Sorting
 *
 * @author		James Hunt
 * @copyright		2004 James Hunt and the OIC Group, Inc.
 * @version		0.95
 */

/**
 * SYS Flag for Sorting Subsystem.
 * The definition of this constant lets other parts
 * of the system know that the Sorting Subsystem
 * has been included for use.
 */
define("SYS_SORTING",1);

function pathos_sorting_byRankAscending($a,$b) {
	return ($a->rank < $b->rank ? -1 : 1);
}

function pathos_sorting_byRankDescending($a,$b) {
	return ($a->rank > $b->rank ? -1 : 1);
}

function pathos_sorting_byNameAscending($a,$b) {
	return strnatcasecmp($a->name,$b->name);
}

function pathos_sorting_byNameDescending($a,$b) {
	return (strnatcasecmp($a->name,$b->name) == -1 ? 1 : -1);
}

function pathos_sorting_byUserNameAscending($a,$b) {
	return strnatcasecmp($a->username,$b->username);
}

function pathos_sorting_byUserNameDescending($a,$b) {
	return (strnatcasecmp($a->username,$b->username) == -1 ? 1 : -1);
}

function pathos_sorting_byPostedAscending($a,$b) {
	return ($a->posted < $b->posted ? -1 : 1);
}

function pathos_sorting_byPostedDescending($a,$b) {
	return ($a->posted > $b->posted ? -1 : 1);
}

function pathos_sorting_byUpdatedAscending($a,$b) {
	return ($a->updated < $b->updated ? -1 : 1);
}

function pathos_sorting_byUpdatedDescending($a,$b) {
	return ($a->updated > $b->updated ? -1 : 1);
}

function pathos_sorting_moduleByNameAscending($a,$b) {
	return strnatcasecmp($a->name(),$b->name());
}

function pathos_sorting_moduleByNameDescending($a,$b) {
	return (strnatcasecmp($a->name(),$b->name()) == -1 ? 1 : -1);
}

function pathos_sorting_moduleClassByNameAscending($a,$b) {
	return strnatcasecmp(call_user_func(array($a,"name")),call_user_func(array($b,"name")));
}

function pathos_sorting_moduleClassByNameDescending($a,$b) {
	return (strnatcasecmp(call_user_func(array($a,"name")),call_user_func(array($b,"name"))) == -1 ? 1 : -1);
}

function pathos_sorting_workflowRevisionAscending($a,$b) {
	return strnatcmp($a->wf_major.".".$a->wf_minor,$b->wf_major.".".$b->wf_minor);
}

function pathos_sorting_workflowRevisionDescending($a,$b) {
	return (strnatcmp($a->wf_major.".".$a->wf_minor,$b->wf_major.".".$b->wf_minor) == -1 ? 1 : -1);
}

?>