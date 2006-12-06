<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Copyright (c) 2006 Maxim Mueller
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

// Part of the HTMLArea category
//should be moved to EditorControl or ToolbarItem

if (!defined("EXPONENT")) exit("");

$loc = exponent_core_makeLocation('administrationmodule');

if (exponent_permissions_check('htmlarea', $loc)) {
	$dm = $db->selectObject('toolbar_' . SITE_WYSIWYG_EDITOR, "id=" . intval($_GET['id']));

	$template = new template("administrationmodule", "_form_EditorControl_Toolbar", $loc);

	if($dm) {
		$template->assign("dm", $dm);
		$template->assign("toolbar", $dm->data);
	}	

	$template->output();

} else {
	echo SITE_403_HTML;
}
?>
