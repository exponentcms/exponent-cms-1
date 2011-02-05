<?php

##################################################
#
# Copyright (c) 2004-2011 OIC Group, Inc.
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

$item = $db->selectObject('calendar','id='.intval($_GET['id']));
if ($item) {
	$loc = unserialize($item->location_data);
	$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$item->id);
	
	if (exponent_permissions_check('delete',$loc) ||
		exponent_permissions_check('delete',$iloc)
	) {
		$db->delete('calendar','id='.$item->id);
		$db->delete('eventdate','event_id='.$item->id);
		$db->delete("calendar_wf_info","real_id=".$_GET['id']);
		$db->delete("calendar_revision","wf_original=".$_GET['id']);		
		//Delete search entries
		$db->delete('search',"ref_module='calendarmodule' AND ref_type='calendar' AND original_id=".$item->id);
		exponent_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>
