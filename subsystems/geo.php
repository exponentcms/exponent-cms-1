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
 * Geography Subsystem
 *
 * The Geography (geo) Subsystem provides the means to
 * query a database of country / region information.
 *
 *
 * @package		Subsystems
 * @subpackage	Geography
 *
 * @author		James Hunt
 * @copyright		2004 James Hunt and the OIC Group, Inc.
 * @version		0.95
 */

/**
 * SYS flag for Geo Subsystem
 *
 * The definition of this constant lets other parts of the subsystem know
 * that the Geo Subsystem has been included for use.
 */
define("SYS_GEO",1);

/**
 * List all Countries in the Geo Database
 *
 * @return Array an array of country objects.
 */
function pathos_geo_listCountriesOnly() {
	global $db;
	$countries = array();
	foreach ($db->selectObjects("geo_country") as $c) {
		$countries[$c->id] = $c->name;
	}
	uasort($countries,"strnatcasecmp");
	return $countries;
}

/**
 * List Countries and Regions in the Geo Database
 *
 * @return Array a two-tiered array of countries and regions.
 */
function pathos_geo_listCountriesAndRegions() {
	global $db;
	$countries = array();
	foreach ($db->selectObjects("geo_country") as $c) {
		$countries[$c->id] = null;
		$countries[$c->id]->name = $c->name;
		$countries[$c->id]->regions = array();
		
		foreach ($db->selectObjects("geo_region","country_id=".$c->id) as $r) {
			$countries[$c->id]->regions[$r->id] = $r->name;
		}
		uasort($countries[$c->id]->regions,"strnatcasecmp");
	}
	if (!defined("SYS_SORTING")) include_once(BASE."subsystems/sorting.php");
	uasort($countries,"pathos_sorting_byNameAscending");
	return $countries;
}

/**
 * List Regions for a specific Country
 *
 * @param integer $country_id The id of the country to get regions for
 * @return Array an array of regions.
 */
function pathos_geo_listRegions($country_id) {
	global $db;
	$regions = array();
	foreach ($db->selectObjects("geo_region","country_id=".$country_id) as $r) {
		$regions[$r->id] = $r->name;
	}
	uasort($regions,"strnatcasecmp");
	return $regions;
}

/**
 * List all Regions in the Geo Database
 *
 * @return Array an array of regions
 */
function pathos_geo_listAllRegions() {
	global $db;
	$regions = array();
	foreach ($db->selectObjects("geo_region") as $r) {
		$regions[$r->id] = $r->name;
	}
	return $regions;
}


?>