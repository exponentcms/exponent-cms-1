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

if ($user && $user->is_acting_admin == 1) {
	$oldpolicy = null;
	if (isset($_POST['id'])) $oldpolicy = $db->selectObject("approvalpolicy","id=".$_POST['id']);
	
	$policy = approvalpolicy::update($_POST,$oldpolicy);
	
	if (!defined("SYS_WORKFLOW")) include_once(BASE."subsystems/workflow.php");
	
	if ($oldpolicy) {
		// Check for content in revision:
		$infos = array();
		foreach(pathos_workflow_getInfoTables() as $table) {
			$typename = str_replace("_wf_info","",$table);
			$infos[$typename] = $db->selectObjects($table,"policy_id=".$oldpolicy->id);
			for ($i = 0; $i < count($infos[$typename]); $i++) {
				$infos[$typename][$i]->state = unserialize($infos[$typename][$i]->current_state_data);
				$revision = $db->selectObject($typename."_wf_revision","wf_original=".$infos[$typename][$i]->real_id." AND wf_major=".$infos[$typename][$i]->current_major." AND wf_minor=".$infos[$typename][$i]->current_minor);
				$infos[$typename][$i]->title = $revision->title;
				$infos[$typename][$i]->approvers = count($infos[$typename][$i]->state[0]);
				$infos[$typename][$i]->approvals = array_sum($infos[$typename][$i]->state[1]);
				$l = unserialize($revision->location_data);
				$infos[$typename][$i]->source = pathos_core_translateLocationSource($l->src);
				$mclass = $l->mod;
				$m = new $mclass();
				$infos[$typename][$i]->module = $m->name();
			}
			if (!count($infos[$typename])) unset($infos[$typename]);
		}
		
		if (count($infos)) {
			$template = new template("workflow","_policy_affectedrevisions",$loc);
			
			$template->assign("affected",$infos);
			$template->assign("oldpolicy",$oldpolicy);
			$template->assign("newpolicy",$policy);
			$template->assign("newpolicy_serial",serialize($policy)); // meant for passthru on the form.
			
			$template->output();
		} else {
			// no outstanding references.
			$db->updateObject($policy,"approvalpolicy","id=".$_POST['id']);
			pathos_flow_redirect();
		}
	} else {
		$pid = $db->insertObject($policy,"approvalpolicy");
		// Setup defaults for workflow actions
		$action = null;
		$action->method = "Redirect User";
		$action->parameters= ""; // safeguard
		$action->policy_id = $pid;
		$action->rank = 0;
		$types = array(
			SYS_WORKFLOW_ACTION_POSTED,
			SYS_WORKFLOW_ACTION_EDITED,
			SYS_WORKFLOW_ACTION_APPROVED_APPROVED,
			SYS_WORKFLOW_ACTION_APPROVED_EDITED,
			SYS_WORKFLOW_ACTION_APPROVED_DENIED,
			SYS_WORKFLOW_ACTION_DELETED,
			SYS_WORKFLOW_ACTION_RESTARTED,
			SYS_WORKFLOW_ACTION_IMPLICIT_APPROVAL,
			SYS_WORKFLOW_ACTION_POSTED_ADMIN,
			SYS_WORKFLOW_ACTION_APPROVED_ADMIN
		);
		
		foreach ($types as $type) {
			$action->type = $type;
			$db->insertObject($action,"workflowaction");
		}
		
		$action->method = "Show Message";
		$action->parameters = "Thank you for your participation in this approval process.  The content you approved has met all of the required criteria, and has been published live.";
		$action->rank = 0;
		$action->type = SYS_WORKFLOW_ACTION_APPROVED_FINAL;
		$db->insertObject($action,"workflowaction");
		
		$action->method = "Show Back Link";
		$action->parameters = "";
		$action->rank = 1;
		$action->type = SYS_WORKFLOW_ACTION_APPROVED_FINAL;
		$db->insertObject($action,"workflowaction");
		
		pathos_flow_redirect();
	}
}

?>