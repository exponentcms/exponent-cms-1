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

if ($user && $user->is_acting_admin) {

	$policy = $db->selectObject("approvalpolicy","id=".$_GET['id']);
	
	if ($policy) {

		if (!defined("SYS_WORKFLOW")) include_once(BASE."subsystems/workflow.php");
		$infos = array();
		foreach(pathos_workflow_getInfoTables() as $table) {
			$typename = str_replace("_wf_info","",$table);
			$infos[$typename] = $db->selectObjects($table,"policy_id=".$policy->id);
			for ($i = 0; $i < count($infos[$typename]); $i++) {
				$revision = $db->selectObject($typename."_wf_revision","wf_original=".$infos[$typename][$i]->real_id." AND wf_major=".$infos[$typename][$i]->current_major." AND wf_minor=".$infos[$typename][$i]->current_minor);
				$infos[$typename][$i]->title = $revision->title;
				$l = unserialize($revision->location_data);
				$infos[$typename][$i]->source = pathos_core_translateLocationSource($l->src);
				$mclass = $l->mod;
				$m = new $mclass();
				$infos[$typename][$i]->module = $m->name();
			}
			if (!count($infos[$typename])) unset($infos[$typename]);
		}
		
		if (count($infos)) {
			$template = new Template("workflow","_confirmpolicydelete",$loc);
			
			$template->assign("affected",$infos);
			$template->assign("policy",$policy);
			
			$template->output();
		} else {
			$db->delete("approvalpolicy","id=".$policy->id);
			$db->delete("approvalpolicyassociation","policy_id=".$policy->id);
			$db->delete("workflowaction","policy_id=".$policy->id);
			pathos_flow_redirect();
		}
	} else echo SITE_404_HTML;
}

?>