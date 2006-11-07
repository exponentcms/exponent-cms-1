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
# $Id: upload_multiple.php,v 1.6 2005/04/26 02:56:05 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

$gallery = null;
if (isset($_GET['gid'])) {
	$gallery = $db->selectObject("imagegallery_gallery","id=".$_GET['gid']);
	if ($gallery) {
		$loc = unserialize($gallery->location_data);
	}
}

if ($gallery) {
	if (exponent_permissions_check('manage',$loc)) {
		if (!defined("SYS_FORMS")) require_once(BASE."subsystems/forms.php");
		exponent_forms_initialize();
		
		$form = new form();
		$form->location($loc);
		$form->meta("action","save_multiple");
		$form->meta("count",$_GET['count']);
		$form->meta("gid",$_GET['gid']);
		
		for ($i = 0; $i < $_GET['count']; $i++) {
			if ($i) $form->register($i,"",new htmlcontrol("<hr size='1' />"));
			$form->register("name$i","Name",new textcontrol());
			$form->register("file$i","File",new uploadcontrol());
		}
		$form->register("submit","",new buttongroupcontrol("Upload","","Cancel"));
		
		$template = new template("imagegallerymodule","_form_multiple",$loc);
		$template->assign("form_html",$form->toHTML());
		$template->output();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>