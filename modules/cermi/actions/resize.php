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

if (!defined('EXPONENT')) exit('');

$id = intval($_REQUEST['id']);
$file = $db->selectObject('file', 'id='.$id);
echo json_encode($file);
//$template = new template('cermi','_resize');
//$template->assign('file', $file);
//echo $template->render();	

?>
