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

if (!defined("EXPONENT")) exit("");
if (!defined("SYS_USER")) require_once(BASE."subsystems/users.php");
if (!defined("SYS_FORMS")) require_once(BASE."subsystems/forms.php");
exponent_forms_initialize();
global $user;
$f = $db->selectObject("wizard_form","id=".intval($_POST['id']));
$rpt = $db->selectObject("wizard_report","form_id=".intval($_POST['id']));
$controls = $db->selectObjects("wizard_control","form_id=".$f->id." and is_readonly=0");
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
if (!isset($_POST['data_id']) || (isset($_POST['data_id']) && exponent_permissions_check("editdata",unserialize($f->location_data)))) {
	if ($f->is_saved == 1) {	
		if (isset($_POST['data_id'])) {
			//if this is an edit we remove the record and insert a new one.
			$olddata = $db->selectObject('wizard_'.$f->table_name,'id='.intval($_POST['data_id']));
			$db_data->ip = $olddata->ip;
			$db_data->user_id = $olddata->user_id;
			$db_data->timestamp = $olddata->timestamp;
			$db->delete('wizard_'.$f->table_name,'id='.intval($_POST['data_id']));
		} 
		else {
			$db_data->ip = $_SERVER['REMOTE_ADDR'];
			if (exponent_sessions_loggedIn()) {
				$db_data->user_id = $user->id;
			} else {
				$db_data->user_id = 0;
			}
			$db_data->timestamp = time();
		}
		$db->insertObject($db_data, 'wizard_'.$f->table_name);
	}
	
	//Email stuff here...
	//Don't send email if this is an edit.
	if ($f->is_email == 1 && !isset($_POST['data_id'])) {
		//Building Email List...
		$emaillist = array();
		foreach ($db->selectObjects("wizard_address","form_id=".$f->id) as $address) {
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
			$template = new template("wizard","_default_report");
		} else {
			$template = new template("wizard","_custom_report");
			$template->assign("template",$rpt->text);
		}
		$template->assign("fields",$fields);
		$template->assign("captions",$captions);
		$template->assign("is_email",1);
		$emailHtml = $template->render();
		
		if (count($emaillist)) {
			//This is an easy way to remove duplicates
			$emaillist = array_flip(array_flip($emaillist));
			
			if (count($emaillist)) {
				//This is an easy way to remove duplicates
				$emaillist = array_flip(array_flip($emaillist));
				
				if (!defined("SYS_SMTP")) include_once(BASE."subsystems/smtp.php");
				$headers = array(
					"MIME-Version"=>"1.0",
					"Content-type"=>"text/html; charset=iso-8859-1"
				);
				if (exponent_smtp_mail($emaillist,"",$f->subject,$emailHtml,$headers) == false) {
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
			$template->assign("response_html",$f->response);
			$template->output();
		}
		else {
			exponent_flow_redirect();
		}
	} else {
		echo SITE_403_HTML;
	}
}

?>
