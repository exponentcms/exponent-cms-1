<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Written and Designed by James Hunt
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################

class wizardmodule {
	function name() { return exponent_lang_loadKey('modules/wizardmodule/class.php','module_name'); }
	function description() { return exponent_lang_loadKey('modules/wizardmodule/class.php','module_description'); }
	function author() { return 'Adam Kessler & Greg Otte'; }
	
	function hasSources() { return true; }
	function hasContent() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = "") {
		$i18n = exponent_lang_loadFile('modules/wizardmodule/class.php');
		if ($internal == "") {
			return array(
				"administrate"=>$i18n['perm_administrate'],
				"editform"=>$i18n['perm_editform'],
				"editformsettings"=>$i18n['perm_editformsettings'],
				"editreport"=>$i18n['perm_editformreport'],
				"viewdata"=>$i18n['perm_viewdata'],
				"editdata"=>$i18n['perm_editdata'],
				"deletedata"=>$i18n['perm_deletedata']
			);
		} else {
			return array(
				"administrate"=>$i18n['perm_administrate'],
				"editform"=>$i18n['perm_editform'],
				"editformsettings"=>$i18n['perm_editformsettings'],
				"editreport"=>$i18n['perm_edit_formreport'],
				"viewdata"=>$i18n['perm_viewdata'],
				"editdata"=>$i18n['perm_editdata'],
				"deletedata"=>$i18n['perm_deletedata']
			);
		}
	}
	
	function show($view,$loc = null,$wizard_id="null",$optional_value_1="", $optional_value_2="") {
		global $db;
		if (!defined("SYS_FORMS")) require_once(BASE."subsystems/forms.php");
		exponent_forms_initialize();
		
		$i18n = exponent_lang_loadFile('modules/wizardmodule/class.php');
		
		if (defined("PREVIEW_READONLY") && !defined("SELECTOR")) {
			// Pass
		}  else {
			$formmsg = null;
			$wiz = null;
			//echo serialize($loc); exit();
			//eDebug($loc); exit;
			if ($wizard_id != null) {
				$wiz = $db->selectObject("wizardmodule_config","wizard_id=".$wizard_id." AND location_data='".serialize($loc)."'");
			} else {
				$wiz = $db->selectObject("wizardmodule_config","location_data='".serialize($loc)."'");
			}
			//eDebug($loc);
			//echo serialize($loc);
			if (!$wiz) {
                                $formmsg = "This wizard module has not yet been configured.<br><br>Please configure the module.";
                        } else {
                                //Get the pages associated with this wizard.
                                $wiz_pages = null;
                                $wiz_pages = $db->selectObjects("wizard_pages", "wizard_id=".$wiz->wizard_id, "rank");
                                if (!$wiz_pages) {
                                        $formmsg = "No pages have been defined for this wizard.<br><br>Please add pages to the module.";
                                }
			}

			if ($formmsg) {
				//No wizard or pages found.  Go straight to the template and show the error msg.
				$template = new template("wizardmodule",$view,$loc);
				$template->assign("formmsg", $formmsg);
				$template->register_permissions(array("administrate","configure"),$loc);
				$template->output();
			} else {
				//unset($_SESSION['wiz_form_data']);
				$this->renderPage($wiz_pages[0]->id, $loc, $optional_value_1, $optional_value_2);
			}
		}
	}

	function renderPage($wizard_page_id = null, $loc=null, $optional_value_1, $optional_value_2) {
		//if the id of the page was not passed in then exit.
		if (!$wizard_page_id) {
			echo SITE_404_HTML;
			exit();
		}	
	
		$i18n = exponent_lang_loadFile('modules/wizardmodule/class.php');
		global $db;
		$wiz_page = null;
		$wiz_page = $db->selectObject("wizard_pages", "id=".$wizard_page_id);
		if (!$wiz_page) {
			echo("The page you requested could not be found.");
			exit();
		}

		//Get the wizard config
		$wizard_config = $db->selectObject("wizardmodule_config", "location_data='".serialize($loc)."'");
		//eDebug($loc);
		//eDebug($wizard_config);
		
		//Get the view template
		//$template = new template("wizardmodule",$view,$loc);
		$template = new template("wizardmodule","Default", $loc);

		//Get the first form to be displayed.
		$wiz_form = null;
		$wiz_form = $db->selectObject("wizard_form", "wizard_page_id=".$wizard_page_id);
		if (!$wiz_form) {
			$formmsg = "The form for this page has not been configured yet.<br><br>Please configure the module.";
			$template->assign("no_form", "1");
		} else {			
			$template->assign("no_form", "0");
			global $SYS_FLOW_REDIRECTIONPATH;
			exponent_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_ACTION);
			$SYS_FLOW_REDIRECTIONPATH = "editfallback";
			exponent_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_ACTION);
			$SYS_FLOW_REDIRECTIONPATH = "exponent_default";
		
			$controls = $db->selectObjects("wizard_control","form_id=".$wiz_form->id);
			if (!defined("SYS_SORTING")) require_once(BASE."subsystems/sorting.php");
			usort($controls,"exponent_sorting_byRankAscending");
			
			if (!defined("SYS_FORMS")) require_once(BASE."subsystems/forms.php");
			exponent_forms_initialize();

			$form = new form();
			foreach ($controls as $c) {
				$ctl = unserialize($c->data);
				$ctl->_id = $c->id;
				$ctl->_readonly = $c->is_readonly;
				$ctl_name = $ctl->identifier;
				
				//If the user has already filled out this form and then comes back to it, we will fill 
				//out the form with the already saved data.
				if (isset($_SESSION['wiz_form_data'][$wiz_page->wizard_id][$wiz_form->id]->$ctl_name)) {
					$ctl_value = $_SESSION['wiz_form_data'][$wiz_page->wizard_id][$wiz_form->id]->$ctl_name;
					$ctl->default = $ctl_value;
				}
				$form->register($c->name,$c->caption,$ctl);
			}
			$form->register(uniqid(""),"", new htmlcontrol("<br /><br />"));
			
			$form->meta("module","wizardmodule");
			$form->meta("wizard_page_id",$wizard_page_id);
			$form->meta("wizard_id",$wiz_page->wizard_id);
			$form->meta("form_id",$wiz_form->id);
			$form->meta("optional_value_1",$optional_value_1);
			$form->meta("optional_value_2",$optional_value_2);
			$form->meta("m", $loc->mod);
			$form->meta("s", $loc->src);
			$form->meta("i", $loc->int);
			$formmsg = '';
	
			if (count($controls) == 0) {
				$formmsg .= $i18n['blank_form'].'<br>';
			}
			if ($wizard_config->is_saved == 0 && $wizard_config->is_email == 0) {
				$formmsg .= $i18n['no_actions']; 
			}
			
			$numpages = $db->countObjects("wizard_pages", "wizard_id=".$wiz_page->wizard_id);
			if ($numpages > 1) {							
				$template->assign("numpages", $numpages);
				$template->assign("pagenum", $wiz_page->rank + 1);
				
				if ($wiz_page->rank > 0) {
					$back =  "< Back";
					$last_rank = $wiz_page->rank - 1;
					$last_page = $db->selectObject("wizard_pages", "rank=".$last_rank." && wizard_id=".$wiz_page->wizard_id);
					$form->meta("last_page", $last_page->id);
				} else {
					$back = "";
				}
					
				//If there are more pages then show the next button, if not, show the submit button		
				//echo "Rank: ".$wiz_page->rank."<br>";
				//echo "numpages: ".($numpages - 1)."<br>";
				if ($wiz_page->rank < $numpages - 1) {
					$next = "Next >";
					$next_rank = $wiz_page->rank + 1;
					//eDebug($wiz_page);
					//echo "Next rank: ".$next_rank;
					//echo "Where clause: "."rank=".$next_rank." & wizard_id=".$wiz_page->wizard_id;
					$next_page = $db->selectObject("wizard_pages", "rank=".$next_rank." && wizard_id=".$wiz_page->wizard_id);
					//eDebug($next_page);
					$form->meta("next_page", $next_page->id);
				} else {
					$next = $wizard_config->submitbtn;
				}
		
			} else {
				$next = $wizard_config->submitbtn;
				$back = null;
			}

			$form->register("nextback","",new nextbackcontrol($next,$back,""));
			$form->meta("action","pagenav");

			if ($next == "Submit") {
				if ( (count($controls)) == 0 || ($wizard_config->is_saved == 0 && $wizard_config->is_email == 0) ) {
					$form->controls['nextback']->disabled = true;
				} 
			}

			//Assign the form to the view.
			$template->assign("page_id", $wiz_page->id);	
			$template->assign("wizard_id", $wiz_page->wizard_id);	
			$template->assign("form_html",$form->toHTML($wiz_form->id));
			$template->assign("form",$wiz_form);
			$template->assign("formmsg",$formmsg);
			$template->register_permissions(array("administrate","configure","editform","editformsettings","editreport","viewdata","editdata","deletedata"),$loc);
			$template->output();
		}
	}
	
	function deleteIn($loc, $wizard_id) {
		global $db;
		$forms = $db->selectObjects("wizard_form","wizard_id=".$wizard_id);
		foreach($forms as $form) {
			$db->delete("wizard_control","form_id=".$form->id);
			$db->dropTable($form->table_name);
		}
		$db->delete("wizard_report","wizard_id=".$wizard_id);
		$db->delete("wizard_address","wizard_id=".$wizard_id);
		$db->delete("wizardmodule_config", "location_data='".serialize($loc)."'");
		$db->delete("wizard_form","wizard_id=".$wizard_id);
		$db->delete("wizard_pages", "wizard_id=".$wizard_id);
	}
	
	function spiderContent($item = null) {
		// No content
		return false;
	}
}

?>
