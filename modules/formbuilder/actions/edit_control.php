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

$f = $db->selectObject("formbuilder_form","id=".$_GET['form_id']);
if ($f) {
	if (pathos_permissions_check("editform",unserialize($f->location_data))) {
		if (isset($_POST['control_type']) && $_POST['control_type']{0} == ".") {
			$htmlctl = new htmlcontrol();
			$htmlctl->identifier = uniqid("");
			$htmlctl->caption = "";
			switch ($_POST['control_type']) {
				case ".break":
					$htmlctl->html = "<br />";
					break;
				case ".line":
					$htmlctl->html = "<hr size='1' />";
					break;
			}
			$ctl->name = uniqid("");
			$ctl->caption = "";
			$ctl->data = serialize($htmlctl);
			$ctl->form_id = $form_id;
			$ctl->is_readonly = 1;
			if (!$db->countObjects("formbuilder_control","form_id=".$form_id)) $ctl->rank = 0;
			else $ctl->rank = $db->max("formbuilder_control","rank","form_id","form_id=".$form_id)+1;
			$db->insertObject($ctl,"formbuilder_control");
			pathos_flow_redirect();
		} else {
			$control_type = "";
			$ctl = null;
			if (isset($_GET['id'])) {
				$control = $db->selectObject("formbuilder_control","id=".$_GET['id']);
				if ($control) {
					$ctl = unserialize($control->data);
					$ctl->identifier = $control->name;
					$ctl->caption = $control->caption;
					$ctl->id = $control->id;
					$control_type = get_class($ctl);
					$f->id = $control->form_id;
				}
			}
			if ($control_type == "") $control_type = $_POST['control_type'];
			$form = call_user_func(array($control_type,"form"),$ctl);
			$form->location($loc);
			if ($ctl) { 
				$form->controls['identifier']->disabled = true;
				$form->meta("id",$ctl->id);
				$form->meta("identifier",$ctl->identifier);
			}
			$form->meta("action","save_control");
			$form->meta('control_type',$control_type);
			$form->meta('form_id',$f->id);
			
			echo $form->toHTML();
		}
	} else echo SITE_403_HTML;
} else echo SITE_404_HTML;
pathos_forms_cleanup();

?>