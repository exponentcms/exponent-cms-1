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

$resource = $db->selectObject("resourceitem","id=".$_GET['id']);
if ($resource != null) {
	$loc = unserialize($resource->location_data);
	$iloc = pathos_core_makeLocation($loc->mod,$loc->src,$resource->id);
	
	if (pathos_permissions_check("delete",$loc) || pathos_permissions_check("delete",$iloc)) {
		foreach ($db->selectObject("resourceitem_wf_revision","wf_original=".$resource->id) as $wf_res) {
			$file = $db->selectObject("file","id=".$wf_res->file_id);
			file::delete($file);
			$db->delete("file","id=".$file->id);
		}
		$db->delete("resourceitem","id=".$resource->id);
		$db->delete("resourceitem_wf_revision","wf_original=".$resource->id);
		pathos_flow_redirect(SYS_FLOW_SECTIONAL);
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>