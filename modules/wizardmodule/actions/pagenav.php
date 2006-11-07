<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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
# $Id: new_image.php,v 1.1 2005/04/18 01:27:23 filetreefrog Exp $
##################################################

if (!defined('EXPONENT')) exit('');

if (!defined("SYS_FORMS")) require_once(BASE."subsystems/forms.php");
exponent_forms_initialize();

if (!defined("SYS_USER")) require_once(BASE."subsystems/users.php");
include_once(BASE.'modules/wizardmodule/class.php');

//Get the form
$form = $db->selectObject("wizard_form", "id=".$_POST['form_id']);

//Rebuild the location information
$loc->mod = $_POST['m'];
$loc->src = $_POST['s'];
$loc->int = $_POST['i'];

//Get the wizard config
$wizard_config = $db->selectObject("wizardmodule_config", "location_data='".serialize($loc)."'");

//Get the form data (this includes the table name, control names and control values)
if (isset($_SESSION['wiz_form_data'][$_POST['wizard_id']])) { 
	$form_data = $_SESSION['wiz_form_data'][$_POST['wizard_id']];
} else {
	$form_data = array();
}

//Get the list of controls that are on the form then add the controls & their values to $form_data
$controls = $db->selectColumn("wizard_control","name","form_id=".$_POST['form_id']); 
foreach ($controls as $key => $value) {
	if (isset($_POST[$value])) {
		$object->$value = $_POST[$value];
		$form_data[$form->id] = $object;
	}	
}
$_SESSION['wiz_form_data'][$_POST['wizard_id']] = $form_data;


if ($_POST['nextback'] == "Next >") {
	wizardmodule::renderPage($_POST['next_page'], $loc, $_POST['optional_value_1'], $_POST['optional_value_2']);
} elseif ($_POST['nextback'] == "< Back") {
	wizardmodule::renderPage($_POST['last_page'], $loc, $_POST['optional_value_1'],$_POST['optional_value_2']);
} elseif ($_POST['nextback'] == $wizard_config->submitbtn) {

//Save the each form's data to the table associated with that form
$all_forms = $db->selectObjects("wizard_form","wizard_id=".$_POST['wizard_id']);
//eDebug($all_forms);
foreach ($all_forms as $form) {
	//Save the data from each form to it's table
	$form_data = $_SESSION['wiz_form_data'][$_POST['wizard_id']][$form->id];
	$form_data->optional_value_1 = $_POST['optional_value_1'];
	$form_data->optional_value_2 = $_POST['optional_value_2'];
	$form_data->user_id = $_SESSION[SYS_SESSION_KEY]['user']->id;
	if ($wizard_config->is_saved == 1) {
		if (isset($form_data)) {
			//eDebug($form_data); exit();
			//eDebug($form_data);eDebug($wizard_config);eDebug($form);
			$db->insertObject($form_data,$form->table_name);
			//echo "Inserting";
		}		
		
	}

	//Send out if emails if this wizard is configured to do that
	if ($wizard_config->is_email == 1) {
		$rpt = $db->selectObject("wizard_report","wizard_id=".intval($_POST['wizard_id']));
		$controls = $db->selectObjects("wizard_control","form_id=".$form->id." and is_readonly=0");
		if (!defined("SYS_SORTING")) require_once(BASE."subsystems/sorting.php");
		usort($controls,"exponent_sorting_byRankAscending");

		$db_data = null;
		$fields = array();
		$captions = array();
		foreach ($controls as $c) {
        		$ctl = unserialize($c->data);
        		$control_type = get_class($ctl);
        		$def = call_user_func(array($control_type,"getFieldDefinition"));
        		if ($def != null) {
        			$value = call_user_func(array($control_type,'parseData'),$c->name,$_POST,true);
              			$varname = $c->name;
              			$db_data->$varname = $value;
               			$fields[$c->name] = call_user_func(array($control_type,'templateFormat'),$value,$ctl);
                		$captions[$c->name] = $c->caption;
        		}
		}

		$emaillist = array();
                foreach ($db->selectObjects("wizard_address","wizard_id=".$_POST['wizard_id']) as $address) {
                	if ($address->group_id != 0) {
                        	foreach (exponent_users_getUsersInGroup(exponent_user_getGroupById($address->group_id)) as $locUser){
                                	if ($locUser->email != '') $emaillist[] = $locUser->email;
                                }
                        } else if ($address->user_id != 0) {
                        	$locUser = exponent_users_getUserById($address->user_id);
                        	if ($locUser->email != '') $emaillist[] = $locUser->email;
                        } else if ($address->email != '') {
                               	$emaillist[] = $address->email;
                        }
                }
	
		if ($rpt->text == "") {
                       	$template = new template("wizardmodule","_default_report");
                } else {
                       	$template = new template("wizardmodule","_custom_report");
                       	$template->assign("template",$rpt->text);
                }
                $template->assign("fields",$fields);
                $template->assign("captions",$captions);
                $template->assign("is_email",1);
                $emailHtml = $template->render();

                if (count($emaillist)) {
                	//This is an easy way to remove duplicates
                        $emaillist = array_flip(array_flip($emaillist));

                        if (!defined("SYS_SMTP")) include_once(BASE."subsystems/smtp.php");
                        $headers = array(
                                        "MIME-Version"=>"1.0",
                                        "Content-type"=>"text/html; charset=iso-8859-1"
                        );
                        if (exponent_smtp_mail($emaillist,"",$wizard_config->subject,$emailHtml,$headers) == false) {
                        	$i18n = exponent_lang_loadFile('modules/wizard/actions/submit_form.php');
                                echo $i18n['err_smtp'];
                        }
                }
	}

        //If is a new post show response, otherwise redirect to the flow.
        if (!isset($_POST['data_id'])) {
        	$template = new template("wizard","_view_response");
                global $SYS_FLOW_REDIRECTIONPATH;
                $SYS_FLOW_REDIRECTIONPATH = "editfallback";
                $template->assign("backlink",exponent_flow_get());
                $SYS_FLOW_REDIRECTIONPATH = "exponent_default";
                $template->assign("response_html",$wizard_config->response);
                $template->output();
                }
        else {
        	exponent_flow_redirect();
        }

} //end foreach
//exit();
//If the wizard is being run inside another module then redirect back to the referring page.
$redirecturl = null;
$redirecturl = exponent_sessions_get('wizard_redirecturl');
if ($redirecturl != null) {
	header('Location: ' . $redirecturl);
       	exit();
}

$template = $template = new template("wizardmodule","_submit_reponse");
$template->assign("response", $wizard_config->response);
$template->output();
//unset ($_SESSION['wiz_form_data'][$_POST['wizard_id']]);
}

?> 
