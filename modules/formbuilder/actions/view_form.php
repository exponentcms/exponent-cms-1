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

if (!defined("SYS_FORMS")) require_once(BASE."subsystems/forms.php");
pathos_forms_initialize();

$f = null;
if (isset($_GET['id'])) $f = $db->selectObject("formbuilder_form","id=".$_GET['id']);

if ($f) {
	if (pathos_permissions_check("editform",unserialize($f->location_data))) {
		pathos_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_ACTION);
		$loc = unserialize($f->location_data);
		$controls = $db->selectObjects("formbuilder_control","form_id=".$f->id);
		if (!defined("SYS_SORTING")) require_once(BASE."subsystems/sorting.php");
		usort($controls,"pathos_sorting_byRankAscending");
		
		$form = new fakeform();
		foreach ($controls as $c) {
			$ctl = unserialize($c->data);
			$ctl->_id = $c->id;
			$ctl->_readonly = $c->is_readonly;
			$ctl->_controltype = get_class($ctl);
			$form->register($c->name,$c->caption,$ctl);
		}
		
		$template = new template("formbuilder","_view_form");
		$template->assign("form_html",$form->toHTML($f->id));
		$template->assign("form",$f);
		global $SYS_FLOW_REDIRECTIONPATH;
		$SYS_FLOW_REDIRECTIONPATH = "editfallback";
		$template->assign("backlink",pathos_flow_get());
		$SYS_FLOW_REDIRECTIONPATH = "pathos_default";
		
		$types = pathos_forms_listControlTypes();
		$types[".break"] = TR_FORMBUILDER_SPACER;
		$types[".line"] = TR_FORMBUILDER_HRLINE;
		uasort($types,"strnatcmp");
		array_unshift($types,TR_FORMBUILDER_PLEASESELECT);
		$template->assign("types",$types);
		$template->assign("pickerurl","source_selector.php?showmodules=formmodule&dest='+escape(\"".PATH_RELATIVE."?module=formbuilder&action=picked_source&form_id=".$f->id."&s=".$loc->src."&m=".$loc->mod ."\")+'&vmod=containermodule&vview=_sourcePicker");
		$template->output();
	} else {
		echo SITE_403_HTML;	
	}
} else {
	echo SITE_404_HTML;
}

?>