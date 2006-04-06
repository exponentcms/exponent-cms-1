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

if (!defined('EXPONENT')) exit('');

$resource = $db->selectObject('resourceitem','id='.intval($_GET['id']));
if ($resource != null) {
	$loc = unserialize($resource->location_data);
	$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$resource->id);
	
	if (exponent_permissions_check('delete',$loc) || exponent_permissions_check('delete',$iloc)) {
		foreach ($db->selectObject('resourceitem_wf_revision','wf_original='.$resource->id) as $wf_res) {
			$file = $db->selectObject('file','id='.$wf_res->file_id);
			file::delete($file);
			$db->delete('file','id='.$file->id);
		}
		$db->delete('resourceitem','id='.$resource->id);
		$db->delete('resourceitem_wf_revision','wf_original='.$resource->id);
		//Delete search entries
		$db->delete('search',"ref_module='resourcesmodule' AND ref_type='resourceitem' AND original_id=".$resource->id);
		
		unset($_SESSION['resource_cache']);
		exponent_flow_redirect(SYS_FLOW_SECTIONAL);
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>
