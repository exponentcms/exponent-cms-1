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
	if (!defined("SYS_USER")) include_once(BASE."subsystems/users.php");
	if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
	if (!defined("SYS_SESSIONS")) include_once(BASE."subsystems/sessions.php");
	pathos_forms_initialize();
	global $user;
	$f = $db->selectObject("formbuilder_form","id=".$_POST['id']);
	$rpt = $db->selectObject("formbuilder_report","form_id=".$_POST['id']);
	$controls = $db->selectObjects("formbuilder_control","form_id=".$f->id." and is_readonly=0");
	if (!defined("SYS_SORTING")) include_once(BASE."subsystems/sorting.php");
	usort($controls,"pathos_sorting_byRankAscending");
	
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
	if (!isset($_POST['data_id']) || (isset($_POST['data_id']) && pathos_permissions_check("editdata",unserialize($f->location_data)))) {
		if ($f->is_saved == 1) {	
			if (isset($_POST['data_id'])) {
				//if this is an edit we remove the record and insert a new one.
				$olddata = $db->selectObject('formbuilder_'.$f->table_name,'id='.$_POST['data_id']);
				$db_data->ip = $olddata->ip;
				$db_data->user_id = $olddata->user_id;
				$db_data->timestamp = $olddata->timestamp;
				$db->delete('formbuilder_'.$f->table_name,'id='.$_POST['data_id']);
			} 
			else {
				$db_data->ip = $_SERVER['REMOTE_ADDR'];
				if (pathos_sessions_loggedIn()) {
					$db_data->user_id = $user->id;
				} else {
					$db_data->user_id = 0;
				}
				$db_data->timestamp = time();
			}
			$db->insertObject($db_data, 'formbuilder_'.$f->table_name);
		}
		
		//Email stuff here...
		//Don't send email if this is an edit.
		if ($f->is_email == 1 && !isset($_POST['data_id'])) {
			//Building Email List...
			$emaillist = array();
			foreach ($db->selectObjects("formbuilder_address","form_id=".$f->id) as $address) {
				if ($address->group_id != 0) {
					foreach (pathos_users_getUsersInGroup($address->group_id) as $locUser){
						if ($locUser->email != '') $emaillist[] = $locUser->email;
					}
				}
				else if ($address->user_id != 0) {
					$locUser = pathos_users_getUserById($address->user_id);
					if ($locUser->email != '') $emaillist[] = $locUser->email;
				}
				else if ($address->email != '') {
					$emaillist[] = $address->email;
				}
			}
			if ($rpt->text == "") {
				$template = new template("formbuilder","_default_report");
			}
			else {
				$template = new template("formbuilder","_custom_report");
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
				if (pathos_smtp_mail($emaillist,"",$f->subject,$emailHtml,$headers) == false) {
					pathos_lang_loadDictionary('modules','formbuilder');
					echo TR_FORMBUILDER_ERR_SMTP;
				}
			}
			
		}
		//If is a new post show response, otherwise redirect to the flow.
		if (!isset($_POST['data_id'])) {
			$template = new template("formbuilder","_view_response");
			global $SYS_FLOW_REDIRECTIONPATH;
			$SYS_FLOW_REDIRECTIONPATH = "editfallback";
			$template->assign("backlink",pathos_flow_get());
			$SYS_FLOW_REDIRECTIONPATH = "pathos_default";
			$template->assign("response_html",$f->response);
			$template->output();
		}
		else {
			pathos_flow_redirect();
		}
	} else echo SITE_403_HTML;
	pathos_forms_cleanup();
?>