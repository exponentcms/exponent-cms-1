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

if (isset($_POST['m']) && isset($_POST['s'])) {
	$floc = exponent_core_makeLocation($_POST['m'],$_POST['s'],$_POST['i']);
	
	if (exponent_permissions_check('configure',$floc)) {
		$data = null;
		$data = $db->selectObject('mediaitem',"location_data='".serialize($floc)."'");
		
		$data = mediaitem::update($_POST,$data);
		$data->location_data = serialize($floc);
				
		$directory = 'files/mediaplayermodule/' .$floc->src; ;
		$filefield = 'media_name';
		if (isset($_FILES[$filefield]) && $_FILES[$filefield]['name'] != '') {
			if (isset($data->media_id) && ($data->media_id != 0)) {
				$file = $db->selectObject('file','id='.$data->media_id);
				file::delete($file);
				$db->delete('file','id='.$file->id);
			}
			$file = file::update($filefield,$directory,null);
			if ($file != null) {
				$data->media_id = $db->insertObject($file,'file');
			}
		}
		
		if (isset($data->id)) {
			$db->updateObject($data,'mediaitem');
		}
		else {
			$db->insertObject($data,'mediaitem');
		}
		exponent_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>