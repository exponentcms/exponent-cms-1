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

class formmodule {
	function name() { return "Form Module"; }
	function description() { return "Allows the creation of forms that can be emailed and/or stored in the database.<br><font color='red'>This is still in development and is not fully functional yet.</font>"; }
	function author() { return "Greg Otte"; }
	
	function hasSources() { return true; }
	function hasContent() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = "") {
		if ($internal == "") {
			return array(
				"administrate"=>"Administrate",
				"editform"=>"Edit Create Form",
				"editreport"=>"Edit Form Report",
				"viewdata"=>"View Data"
			);
		} else {
			return array(
				"administrate"=>"Administrate",
				"editform"=>"Edit Create Form",
				"editreport"=>"Edit Form Report",
				"viewdata"=>"View Data"
			);
		}
	}
	
	function show($view,$loc = null) {
		global $db;
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
		
		if (defined("PREVIEW_READONLY")) {
			echo "";
		} 
		else {
			$f = null;
			$f = $db->selectObject("formbuilder_form","location_data='".serialize($loc)."'");
			if (!$f) {
				//Create a form if it's missing...
				$f->name = "New Form";
				$f->description = "";
				$f->location_data = serialize($loc);
				$f->table_name = "";
				$f->is_email = 0;
				$f->is_saved = 0;
				$f->submitbtn = "Submit";
				$f->resetbtn = "Reset";
				$f->response = "Your form has been submited.";
				$frmid = $db->insertObject($f,"formbuilder_form");
				//Create Default Report;
				$rpt->name = "Default Report";
				$rpt->description = "This is the auto generated default report. Leave the report defenition blank to use the default 'all fields' report.";
				$rpt->location_data = $f->location_data;
				$rpt->text = "";
				$rpt->column_names = "";
				$rpt->form_id = $frmid;
				$db->insertObject($rpt,"formbuilder_report");
				$f->id = $frmid;
			}
			global $SYS_FLOW_REDIRECTIONPATH;
			pathos_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_ACTION);
			$SYS_FLOW_REDIRECTIONPATH = "editfallback";
			pathos_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_ACTION);
			$SYS_FLOW_REDIRECTIONPATH = "pathos_default";
			
			$loc = unserialize($f->location_data);
			$controls = $db->selectObjects("formbuilder_control","form_id=".$f->id);
			if (!defined("SYS_SORTING")) include_once(BASE."subsystems/sorting.php");
			usort($controls,"pathos_sorting_byRankAscending");
			
			$form = new form();
			foreach ($controls as $c) {
				$ctl = unserialize($c->data);
				$ctl->_id = $c->id;
				$ctl->_readonly = $c->is_readonly;
				$form->register($c->name,$c->caption,$ctl);
			}
			$form->register(uniqid(""),"", new htmlcontrol("<br><br>"));
			$form->register("submit","",new buttongroupcontrol($f->submitbtn,$f->resetbtn,""));
			$template = new template("formmodule",$view);
			$template->assign("form_html",$form->toHTML($f->id));
			$template->assign("form",$f);
			$types = pathos_forms_listControlTypes();
			$types[".break"] = "Spacer";
			$types[".line"] = "Horizontal Line";
			uasort($types,"strnatcmp");
			array_unshift($types,"[Please Select]");
			$template->assign("types",$types);
			$template->output();
		}
		
		pathos_forms_cleanup();
	}
	
	function deleteIn($loc) {
		global $db;
		foreach ($db->selectObjects("formbuilder_form","location_data='".serialize($loc)."'") as $form) {
			$db->delete("formbuilder_control","form_id=".$form->id);
		}
		$db->delete("formbuilder_report","form_id=".$form->id);
		$db->delete("formbuilder_form","location_data='".serialize($loc)."'");
	}
	
	function copyContent($oloc,$nloc) {
		// IMPLEMENTME
	}
}

?>