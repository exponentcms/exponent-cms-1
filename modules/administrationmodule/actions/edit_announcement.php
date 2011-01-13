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

if (exponent_users_isAdmin()) {
	$announcement = null;
	$announcement = $db->selectObject('announcement', 1);
	$form = announcement::form($announcement);
	$form->meta('module','administrationmodule');
	$form->meta('action','save_announcement');	
	$template = new template('administrationmodule','_form_edit_announcement');
	$template->assign('form_html',$form->toHTML());
	$template->output();	
} else {
	echo SITE_403_HTML;
}

?>
