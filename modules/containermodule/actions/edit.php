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

$container = null;
$iloc = null;
$cloc = null;
if (isset($_GET['id'])) {
	$container = $db->selectObject("container","id=".$_GET['id']);
	if ($container != null) {
		$iloc = unserialize($container->internal);
		$cloc = unserialize($container->external);
		$cloc->int = $container->id;
	}
} else {
	$container->rank = $_GET['rank'];
}

if (pathos_permissions_check("edit_module",$loc) || pathos_permissions_check("add_module",$loc) ||
	($iloc != null && pathos_permissions_check("administrate",$iloc)) ||
	($cloc != null && pathos_permissions_check("delete_module",$cloc))
) {
	#
	# Initialize Container, in case its null
	#
	$locref = null;
	if (!isset($container->id)) {
		$locref->description = "";
		$container->view = "";
		$container->internal = pathos_core_makeLocation();
		$container->title = "";
		$container->rank = $_GET['rank'];
	} else {
		$container->internal = unserialize($container->internal);
		$locref = $db->selectObject("locationref","module='".$container->internal->mod."' AND source='".$container->internal->src."'","container_edit");
	}
	
	$template = new template("containermodule","_form_edit",$loc);
	$template->assign("rerank",(isset($_GET['rerank']) ? 1 : 0));
	$template->assign("container",$container);
	$template->assign("locref",$locref);
	$template->assign("is_edit",isset($container->id));
	$template->assign("can_activate_modules",$user->is_acting_admin);
	$template->assign("current_section",pathos_sessions_get('last_section'));
	
	if (!defined("SYS_JAVASCRIPT")) include_once(BASE."subsystems/javascript.php");
	$haveclass = false;
	$mods = array();
	
	$modules_list = (isset($container->id) ? pathos_modules_list() : pathos_modules_listActive());
	
	if (!count($modules_list)) { // No active modules
		$template->assign("nomodules",1);
	} else {
		$template->assign("nomodules",0);
	}
	
	if (!defined("SYS_SORTING")) include_once(BASE."subsystems/sorting.php");
	usort($modules_list,"pathos_sorting_moduleClassByNameAscending");
	
	$js_init = "<script type='text/javascript'>";
		
	foreach ($modules_list as $moduleclass) {
		$module = new $moduleclass();
		
		$mod = null;
		
		// Get basic module meta info
		$mod->name = $module->name();
		$mod->author = $module->author();
		$mod->description = $module->description();
		if (isset($container->view) && $container->internal->mod == $moduleclass) {
			$mod->defaultView = $container->view;
		} else $mod->defaultView = DEFAULT_VIEW;
		
		// Get support flags
		$mod->supportsSources = ($module->hasSources() ? 1 : 0);
		$mod->supportsViews  = ($module->hasViews()   ? 1 : 0);
		
		// Get a list of views
		$mod->views = pathos_template_listModuleViews($moduleclass);
		natsort($mod->views);
		
		if (!$haveclass) {
			$js_init .=  pathos_javascript_class($mod,"Module");
			$js_init .=  "var modules = new Array();\n";
			$js_init .=  "var modnames = new Array();\n\n";
			$haveclass = true;
		}
		$js_init .=  "modules.push(" . pathos_javascript_object($mod,"Module") . ");\n";
		$js_init .=  "modnames.push('" . $moduleclass . "');\n";
		$mods[$moduleclass] = $module->name();
	}
	$js_init .= "\n</script>";
	
	$template->assign("js_init",$js_init);
	$template->assign("modules",$mods);
	$template->assign("loc",$loc);
	$template->assign("back",pathos_flow_get());
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>