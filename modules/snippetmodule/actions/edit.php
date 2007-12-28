<?php

##################################################
#
# Copyright (c) 2004-2008 OIC Group, Inc.
# Written and Designed by Ron Miller
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

$textitem = null;
if (isset($_GET['id'])) {
	$textitem = $db->selectObject('textitem','id=' . intval($_GET['id']));
}

if ($textitem != null) {
	$loc = unserialize($textitem->location_data);
}

if (exponent_permissions_check('edit',$loc)) {
	$form = snippetitem::form($textitem);
	$form->location($loc);
	$form->meta('action','save');
	
	$template = new template('textmodule','_form_edit',$loc);
	$template->assign('form_html',$form->toHTML());
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>