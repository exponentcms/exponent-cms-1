<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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

if (!defined("PATHOS")) exit("");

$i18n = pathos_lang_loadFile('modules/formbuilder/actions/save_control.php');

if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
pathos_forms_initialize();
$f = $db->selectObject('formbuilder_form','id='.intval($_POST['form_id']));
if ($f) {
	if (pathos_permissions_check('editform',unserialize($f->location_data))) {	
		$ctl = null;
		$control = null;
		if (isset($_POST['id'])) {
			$control = $db->selectObject('formbuilder_control','id='.intval($_POST['id']));
			if ($control) {
				$ctl = unserialize($control->data);
				$ctl->identifier = $control->name;
				$ctl->caption = $control->caption;
			}
		}
	
		$ctl = call_user_func(array($_POST['control_type'],'update'),$_POST,$ctl);
		if ($ctl != null) {
			$name = preg_replace('/[^A-Za-z0-9]/','_',$ctl->identifier);
			if (!isset($_POST['id']) && $db->countObjects('formbuilder_control',"name='".$name."' and form_id=".intval($_POST['form_id'])) > 0) {
				$post = $_POST;
				$post['_formError'] = $i18n['bad_id'];
				pathos_sessions_set('last_POST',$post);
			} 
			elseif ($name=='id' || $name=='ip' || $name=='user_id' || $name=='timestamp') {
				$post = $_POST;
				$post['_formError'] = sprintf($i18n['reserved_id'],$name);
				pathos_sessions_set('last_POST',$post);
			} else {
				if (!isset($_POST['id'])) {
					$control->name =  $name;
				}
				$control->caption = $ctl->caption;
				$control->form_id = intval($_POST['form_id']);
				$control->is_static = (isset($ctl->is_static)?$ctl->is_static:0);
				$control->data = serialize($ctl);
				
				if (isset($control->id)) {
					$db->updateObject($control,'formbuilder_control');
				} else {
					if (!$db->countObjects('formbuilder_control','form_id='.$control->form_id)) {
						$control->rank = 0;
					} else {
						$control->rank = $db->max('formbuilder_control','rank','form_id','form_id='.$control->form_id)+1;
					}
					$db->insertObject($control,'formbuilder_control');
				}
				
				formbuilder_form::updateTable($f);
			}
		}
		
		pathos_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>