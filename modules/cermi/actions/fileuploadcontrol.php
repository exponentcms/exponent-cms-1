<?php

##################################################
#
# Copyright (c) 2004-2007 OIC Group, Inc.
# Written and Designed by Adam Kessler
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

include_once('../../../exponent.php');

//$file = null;
//if (isset($_GET['id'])) {
//	$file = $db->selectObject('file','id='.intval($_GET['id']));
//}

$files = $db->selectObjects('file', 'item_type="'.$_GET['item_type'].'" AND item_id='.$_GET['item_id']);
$loc = exponent_core_makeLocation('cermi');

//eDebug($_REQUEST);
$template = new template('cermi','_fileuploadcontrol');
//$template->assign('file',$file);
$template->assign('files',$files);
$template->assign('item_type', $_REQUEST['item_type']);
$template->assign('item_id', $_REQUEST['item_id']);
$template->output();

?>
