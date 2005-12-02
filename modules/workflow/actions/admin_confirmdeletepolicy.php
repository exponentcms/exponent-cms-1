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

if (!defined('PATHOS')) exit('');

if (pathos_permissions_check('workflow',pathos_core_makeLocation('administrationmodule'))) {

	$policy = $db->selectObject('approvalpolicy','id='.intval($_GET['id']));
	
	if ($policy) {

		if (!defined('SYS_WORKFLOW')) require_once(BASE.'subsystems/workflow.php');
		$infos = array();
		foreach(pathos_workflow_getInfoTables() as $table) {
			$typename = str_replace('_wf_info','',$table);
			$infos[$typename] = $db->selectObjects($table,'policy_id='.$policy->id);
			for ($i = 0; $i < count($infos[$typename]); $i++) {
				$revision = $db->selectObject($typename.'_wf_revision','wf_original='.$infos[$typename][$i]->real_id.' AND wf_major='.$infos[$typename][$i]->current_major.' AND wf_minor='.$infos[$typename][$i]->current_minor);
				$infos[$typename][$i]->title = $revision->title;
				$l = unserialize($revision->location_data);
				$mclass = $l->mod;
				$m = new $mclass();
				$infos[$typename][$i]->module = $m->name();
			}
			if (!count($infos[$typename])) {
				unset($infos[$typename]);
			}
		}
		
		if (count($infos)) {
			$template = new template('workflow','_confirmpolicydelete',$loc);
			
			$template->assign('affected',$infos);
			$template->assign('policy',$policy);
			
			$template->output();
		} else {
			$db->delete('approvalpolicy','id='.$policy->id);
			$db->delete('approvalpolicyassociation','policy_id='.$policy->id);
			$db->delete('workflowaction','policy_id='.$policy->id);
			pathos_flow_redirect();
		}
	} else {
		echo SITE_404_HTML;
	}
} else {
	echo SITE_403_HTML;
}

?>