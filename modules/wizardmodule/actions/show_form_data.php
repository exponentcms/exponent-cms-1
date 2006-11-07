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

$i18n = exponent_lang_loadFile('modules/formbuilder/actions/view_form.php');

if (!defined("SYS_FORMS")) require_once(BASE."subsystems/forms.php");
exponent_forms_initialize();

$f = null;
if (isset($_GET['id'])) {
	$f = $db->selectObject("wizard_form","id=".intval($_GET['id']));
}

if ($f == true) {
	//if (exponent_permissions_check("editform",unserialize($f->location_data))) {

		$sql = "user_id=".$_GET['user_id'];
		if (isset($_GET['optional_value_1']) && $_GET['optional_value_1'] != "") {
			$sql .= " AND optional_value_1=".$_GET['optional_value_1'];
		}
		
		if (isset($_GET['optional_value_2']) && $_GET['optional_value_2'] != "") {
			$sql .= " AND optional_value_2=".$_GET['optional_value_2'];
		}

		$data = $db->selectObject($f->table_name, $sql);

		exponent_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_ACTION);
		//$loc = unserialize($f->location_data);
		$controls = $db->selectObjects("wizard_control","form_id=".$f->id);
		if (!defined("SYS_SORTING")) require_once(BASE."subsystems/sorting.php");
		usort($controls,"exponent_sorting_byRankAscending");
		
		$form = new form();
		foreach ($controls as $c) {
			$ctl = unserialize($c->data);
			$ctl->_id = $c->id;
			$ctl->_readonly = $c->is_readonly;
			$ctl->_controltype = get_class($ctl);

			if (isset($data)) {
				$name = null;
				$name = $c->name;
                        	$ctl_value = $data->$name;
                                $ctl->default = $ctl_value;
                        }

			$form->register($c->name,$c->caption,$ctl);
		}
		
		$template = new template("wizardmodule","Default");
		$template->assign("form_html",$form->toHTML($f->id,"wizardmodule"));
		$template->assign("form",$f);
		global $SYS_FLOW_REDIRECTIONPATH;
		$SYS_FLOW_REDIRECTIONPATH = "editfallback";
		$template->assign("backlink",exponent_flow_get());
		$SYS_FLOW_REDIRECTIONPATH = "exponent_default";
		
		$types = exponent_forms_listControlTypes();
		$types[".break"] = $i18n['spacer'];
		$types[".line"] = $i18n['line'];
		uasort($types,"strnatcmp");
		array_unshift($types,$i18n['select']);
		$template->assign("types",$types);
		$template->assign("pickerurl","source_selector.php?showmodules=wizardmodule&dest='+escape(\"".PATH_RELATIVE."?module=wizardmodule&action=picked_source&form_id=".$f->id."&s=".$loc->src."&m=".$loc->mod ."\")+'&vmod=containermodule&vview=_sourcePicker");
		$template->output();
	//} else {
		//echo SITE_403_HTML;	
	//}
} else {
	echo SITE_404_HTML;
}

?>
