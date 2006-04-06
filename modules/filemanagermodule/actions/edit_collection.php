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

if (isset($_GET['id']))
    $_GET['id'] = intval($_GET['id']);
	
$collection = null;
if (isset($_GET['id'])) {
	$collection = $db->selectObject('file_collection','id='.$_GET['id']);
}
$loc = exponent_core_makeLocation('filemanagermodule');

// PERM CHECK
	$form = file_collection::form($collection);
	$form->meta('module','filemanagermodule');
	$form->meta('action','save_collection');
	
	$template = new template('filemanagermodule','_form_editCollection');
	$template->assign('form_html',$form->toHTML());
	$template->assign('is_edit',($collection == null ? 0 : 1));
	$template->output();
// END PERM CHECK

?>
