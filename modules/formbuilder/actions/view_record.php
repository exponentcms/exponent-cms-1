<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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
	
	pathos_lang_loadDictionary('modules','formbuilder');
	
	if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
	if (!defined("SYS_USERS")) include_once(BASE."subsystems/users.php");
	pathos_forms_initialize();
	
	$f = $db->selectObject("formbuilder_form","id=".$_GET['form_id']);
	$controls = $db->selectObjects("formbuilder_control","form_id=".$f->id." and is_readonly=0 and is_static = 0");
	$data = $db->selectObject("formbuilder_".$f->table_name,"id=".$_GET['id']);
	$rpt = $db->selectObject("formbuilder_report","form_id=".$_GET['form_id']);
	if ($f && $controls && $data && $rpt) {
		if (pathos_permissions_check("viewdata",unserialize($f->location_data))) {
			if (!defined("SYS_SORTING")) include_once(BASE."subsystems/sorting.php");
			usort($controls,"pathos_sorting_byRankAscending");
			
			
			$fields = array();
			$captions = array();
			foreach ($controls as $c) {
				$ctl = unserialize($c->data);
				$control_type = get_class($ctl);
				$name = $c->name;
				$fields[$name] = call_user_func(array($control_type,'templateFormat'),$data->$name,$ctl);
				$captions[$name] = $c->caption;
			}
			
			$captions['ip'] = TR_FORMBUILDER_FIELD_IP;
			$captions['timestamp'] = TR_FORMBUILDER_FIELD_TIMESTAMP;
			$captions['user_id'] = TR_FORMBUILDER_FIELD_USERNAME;
			$fields['ip'] = $data->ip;
			$locUser =  pathos_users_getUserById($data->user_id);
			$fields['user_id'] =  isset($locUser->username)?$locUser->username:'';
			$fields['timestamp'] = strftime(DISPLAY_DATETIME_FORMAT,$data->timestamp);
		
			if ($rpt->text == "") {
				$template = new template("formbuilder","_default_report");
			}
			else {
				$template = new template("formbuilder","_custom_report");
				$template->assign("template",$rpt->text);
			}
			$template->assign("fields",$fields);
			$template->assign("captions",$captions);
			$template->assign("backlink",pathos_flow_get());
			$template->assign("is_email",0);
			$template->output();
	} else echo SITE_403_HTML;
} else echo SITE_404_HTML;
	
	
?>