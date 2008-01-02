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

$files = $db->selectObjects('file');
$limit = !empty($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 0;
$item_type = !empty($_REQUEST['item_type']) ? $_REQUEST['item_type'] : 'all';
$template = new template('cermi','_main');
$template->assign('files', $files);
$template->assign('limit', $limit);
$template->assign('item_type', $item_type);
$template->output();
?>
