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

$collection = null;
if (isset($_GET['id'])) {
	$collection = $db->selectObject('file_collection','id='.$_GET['id']);
} else {
	$collection->id = 0;
	$collection->name = 'Uncategorized Files';
	$collection->description = 'Theses files have not been categorized yet,';
}
$loc = pathos_core_makeLocation('filemanagermodule');

if ($collection) {
	pathos_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_ACTION);
	
	$template = new template('filemanagermodule','_view');
	$template->assign('collection',$collection);
	
	$files = $db->selectObjects('file','collection_id='.$collection->id);
	if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
	usort($files,'pathos_sorting_byPostedDescending');
	$template->assign('files',$files);
	$template->assign('numfiles',count($files));
	
	$template->output();
} else {
	echo SITE_404_HTML;
}

?>