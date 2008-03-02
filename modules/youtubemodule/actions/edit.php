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

$youtube = null;
if (isset($_GET['id'])) {
	$youtube = $db->selectObject('youtube','id=' . intval($_GET['id']));
}

if (exponent_permissions_check('edit',$loc)) {
	$template = new template('youtubemodule','_form_edit',$loc);
	$template->assign('youtube', $youtube);
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
