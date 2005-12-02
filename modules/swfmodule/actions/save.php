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

if (isset($_POST['m']) && isset($_POST['s'])) {
	$floc = pathos_core_makeLocation($_POST['m'],$_POST['s'],$_POST['i']);
	
	if (pathos_permissions_check('configure',$floc)) {
		$data = null;
		$data = $db->selectObject('swfitem',"location_data='".serialize($floc)."'");
		
		$data = swfitem::update($_POST,$data);
		$data->location_data = serialize($floc);
		
		$directory = 'files/swfmodule/' ;
		$filefield = 'swf_name';
		if (isset($_FILES[$filefield]) && $_FILES[$filefield]['name'] != '') {
			if (isset($data->swf_id) && ($data->swf_id != 0)) {
				$file = $db->selectObject('file','id='.$data->swf_id);
				file::delete($file);
				$db->delete('file','id='.$file->id);
			}
			$file = file::update($filefield,$directory,null);
			if ($file != null) {
				$data->swf_id = $db->insertObject($file,'file');
			}
		}
		
		$filefield = 'alt_image_name';
		if (isset($_FILES[$filefield]) && $_FILES[$filefield]['name'] != '') {
			if (isset($data->alt_image_id) && ($data->alt_image_id != 0)) {
				$file = $db->selectObject('file','id='.$data->alt_image_id);
				file::delete($file);
				$db->delete('file','id='.$file->id);
			}
			$file = file::update($filefield,$directory,null);
			if ($file != null) {
				$data->alt_image_id = $db->insertObject($file,'file');
			}
		}
		
		if (isset($data->id)) {
			$db->updateObject($data,'swfitem');
		}
		else {
			$db->insertObject($data,'swfitem');
		}
		pathos_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

if (pathos_permissions_check('configure',$loc)) {
	$textitem = textitem::update($_POST,$textitem);
	$textitem->location_data = serialize($loc);
	if (!defined('SYS_WORKFLOW')) include_once(BASE.'subsystems/workflow.php');
	pathos_workflow_post($textitem,'textitem',$loc);
} else {
	echo SITE_403_HTML;
}

?>