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

$item = null;
if (isset($_GET['id'])) {
	$item = $db->selectObject('rotator_item','id='.intval($_GET['id']));
}

if ($item) {
	$loc = unserialize($item->location_data);
}

if (exponent_permissions_check('manage',$loc)) {	
	$form = rotator_item::form($item);
	$form->location($loc);
	$form->meta('action','save_item');
	
	$template = new template('rotatormodule','_form_edit');
	$template->assign('is_edit',isset($item->id));
	$template->assign('form_html',$form->toHTML());
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>