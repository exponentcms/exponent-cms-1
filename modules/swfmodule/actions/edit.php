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

$data = null;
$data = $db->selectObject('swfitem',"location_data='" .serialize($loc)."'");

if (pathos_permissions_check('configure',$loc)) {

	$form = swfitem::form($data);
	$form->location($loc);
	$form->meta('action','save');
	$form->meta('m',$loc->mod);
	$form->meta('s',$loc->src);
	$form->meta('i',$loc->int);
	$template = new template('swfmodule','_form_edit',$loc);
	$template->assign('form_html',$form->toHTML());
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>