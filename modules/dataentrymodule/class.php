<?php

##################################################
#
# Copyright 2005 James Hunt and OIC Group, Inc.
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

class dataentrymodule {
	function name() { return "Data Entry Reports Module"; }
	function description() { return "Allows the creation of arbitrary database tables, provides forms to manage table data, and reports to view and analyze it.<br><font color='red'><b>This is not functional yet!!</b></b></font>"; }
	function author() { return "Greg Otte and James Hunt"; }
	
	function hasSources() { return true; }
	function hasContent() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = "") {
		if ($internal == "") {
			return array(
				"administrate"=>"Administrate",
				"configure"=>"Configure",
				"manage_table"=>"Manage Tables",
				"manage_data"=>"Manage Table Data"
			);
		} else {
			return array(
				"administrate"=>"Administrate",
				"configure"=>"Configure",
				"manage_table"=>"Manage Table",
				"manage_data"=>"Manage Table Data"
			);
		}
	}
	
	function show($view,$loc = null) {
		global $db;
	/*
		$tables = $db->selectObjects("dataentry_table","location_data='".serialize($loc)."'");
		$uninstalled = $db->selectObjects("dataentry_table","location_data='".serialize($loc)."' AND installed=0");
		
		$template = new template("dataentrymodule",$view,$loc);
		$template->assign("tables",$tables);
		$template->assign("uninstalled",$uninstalled);
		$template->assign("numToInstall",count($uninstalled));
		$template->register_permissions(
			array("administrate","manage_table","manage_data"),
			$loc
		);
	*/
		$template = new template("dataentrymodule",$view,$loc);
		$forms = $db->selectObjects("dataentry_form","location_data='".serialize($loc)."'");
		$reports = $db->selectObjects("dataentry_report","location_data='".serialize($loc)."'");
		$template->assign("forms",$forms);
		$template->assign("reports",$reports);
		$template->output();
	}
	
	function getFieldTypes() {
		return array(
			DB_DEF_ID=>"ID",
			DB_DEF_INTEGER=>"Integer",
			DB_DEF_TIMESTAMP=>"Date/Time",
			DB_DEF_BOOLEAN=>"Boolean",
			DB_DEF_STRING=>"Text"
		);
	}
	
	function deleteIn($loc) {
		global $db;
		foreach ($db->selectObjects("dataentry_table","location_data='".serialize($loc)."'") as $table) {
			$db->delete("dataentry_field","table_id=".$table->id);
		}
		$db->delete("dataentry_table","location_data='".serialize($loc)."'");
	}
	
	function copyContent($oloc,$nloc) {
		// IMPLEMENTME
	}
}

?>