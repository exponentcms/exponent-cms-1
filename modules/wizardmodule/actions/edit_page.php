<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# Exponent is distributed in the hope that it
# will be useful, but WITHOUT ANY WARRANTY;
# without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR
# PURPOSE.  See the GNU General Public License
# for more details.
#
# You should have received a copy of the GNU
# General Public License along with Exponent; if
# not, write to:
#
# Free Software Foundation, Inc.,
# 59 Temple Place,
# Suite 330,
# Boston, MA 02111-1307  USA
#
# $Id: new_image.php,v 1.1 2005/04/18 01:27:23 filetreefrog Exp $
##################################################

if (!defined('EXPONENT')) exit('');

$page = null;
if (isset($_GET['id'])) {
	$page = $db->selectObject("wizard_pages","id=".$_GET['id']);
        if ($page == null) {
        	echo SITE_404_HTML;
		exit();
        }
} else {
	echo SITE_404_HTML;
	exit();
}

$form = wizard_page::form($page);
$form->meta('module','wizardmodule');
$form->meta('action','save_page');
$form->meta('wizard_id', $page->wizard_id);

$template = new template("wizardmodule","_form_editpage");
$template->assign("is_edit", 1);
$template->assign("form_html",$form->toHTML());
$template->output();	


?> 
