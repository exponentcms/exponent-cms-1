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

if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
pathos_forms_initialize();

$ctl = null;
$control = null;
if (isset($_POST['id'])) {
	$control = $db->selectObject("formbuilder_control","id=".$_POST['id']);
	if ($control) {
		$ctl = unserialize($control->data);
		$ctl->identifier = $control->name;
		$ctl->caption = $control->caption;
	}
}

$ctl = call_user_func(array($_POST['control_type'],'update'),$_POST,$ctl);
	if ($ctl != null) {
		if ((!isset($_POST['id'])) && $db->countObjects("formbuilder_control","name='".$ctl->identifier."' and form_id=".$_POST['form_id']) > 0) {
			$post = $_POST;
			$post['_formError'] = "Identifier must be unique.";
			pathos_sessions_set("last_POST",$post);
		}
		else {
			if (!isset($_POST['id'])) {
				$control->name =  preg_replace("/[^A-Za-z0-9]/","_",$ctl->identifier);
			}
			$control->caption = $ctl->caption;
			$control->form_id = $_POST['form_id'];
			$control->data = serialize($ctl);
			
			if (isset($control->id)) {
				$db->updateObject($control,"formbuilder_control");
			} else {
				if (!$db->countObjects("formbuilder_control","form_id=".$control->form_id)) $control->rank = 0;
				else $control->rank = $db->max("formbuilder_control","rank","form_id","form_id=".$control->form_id)+1;
				$db->insertObject($control,"formbuilder_control");
			}
			$f = $db->selectObject("formbuilder_form","id=".$_POST['form_id']);
			formbuilder_form::updateTable($f);
		}
	}
	pathos_flow_redirect();

?>