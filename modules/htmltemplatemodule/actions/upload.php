<?php

##################################################
#
# Copyright 2004 James Hunt and OIC Group, Inc.
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
# $Id$
##################################################

if (!defined("PATHOS")) exit("");

if (pathos_permissions_check("create",$loc)) {
	$t = null;
	if (isset($_GET['id'])) $t = $db->selectObject("htmltemplate","id=".$_GET['id']);
	
	if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
	pathos_forms_initialize();
	
	pathos_lang_loadDictionary('modules','htmltemplatemodule');
	
	$form = htmltemplate::form($t);
	$form->registerBefore("submit","file",TR_HTMLTEMPLATEMODULE_UPLOAD,new uploadcontrol());
	$form->unregister("body");
	$form->meta("module","htmltemplatemodule");
	$form->meta("action","save_upload");
	
	$template = new template("htmltemplatemodule","_form_upload",$loc);
	$template->assign("form_html",$form->toHTML());
	$template->output();
	
	pathos_forms_cleanup();
} else {
	echo SITE_403_HTML;
}

?>