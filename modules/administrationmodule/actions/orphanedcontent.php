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

if ($user && $user->is_admin == 1) {
	pathos_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);

	$nullrefs = $db->selectObjects("locationref","refcount=0");
	$mods = array();
	$have_bad_orphans = false;
	foreach ($nullrefs as $nullref) {
		$modclass = $nullref->module;
		
		$have_bad_orphans = false;
		
		if (!isset($mods[$nullref->module])) {
			if (class_exists($modclass)) {
				$mod = new $modclass();
				$mods[$nullref->module] = array(
					"name"=>$mod->name(),
					"modules"=>array()
				);
			} else $have_bad_orphans = true;
		}
		if (class_exists($modclass)) {
			ob_start();
			call_user_func(array($modclass,"show"),"Default",pathos_core_makeLocation($modclass,$nullref->source));		
			$mods[$nullref->module]["modules"][$nullref->source] = ob_get_contents();
			ob_end_clean();
		}
	}
	
	$template = new Template("administrationmodule","_orphanedcontent");
	$template->assign("modules",$mods);
	$template->assign("have_bad_orphans",$have_bad_orphans);
	$template->assign("deletelink","?module=administrationmodule&action=orphanedcontent_delete");
	
	$template->output();	
} else {
	echo SITE_403_HTML;
}

?>