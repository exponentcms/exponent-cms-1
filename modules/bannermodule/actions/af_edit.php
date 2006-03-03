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

if (!defined('EXPONENT')) exit('');

if (isset($_GET['id']))
    $_GET['id'] = intval($_GET['id']);
	
$af = null;
if (isset($_GET['id'])) {
	$af = $db->selectObject('banner_affiliate','id='.intval($_GET['id']));
}

if (exponent_permissions_check('manage_af',$loc)) {
	$form = banner_affiliate::form($af);
	$form->meta('module','bannermodule');
	$form->meta('action','af_save');
	
	$template = new template('bannermodule','_form_af_edit',$loc);
	$template->assign('form_html',$form->toHTML());
	$template->assign('is_edit',(isset($_GET['id'])?1:0));
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
