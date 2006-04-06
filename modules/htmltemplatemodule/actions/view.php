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

if (!defined("EXPONENT")) exit("");

// PERM CHECK
	$t = null;
	if (isset($_GET['id'])) {
		$t = $db->selectObject("htmltemplate","id=".intval($_GET['id']));
	}
	if ($t) {
		exponent_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);
	
		$template = new template("htmltemplatemodule","_view",$loc);
		
		$template->assign("template",$t);
		$template->register_permissions(
			array("administrate","edit","delete"),
			$loc);
		$template->output();
	} else {
		echo SITE_404_HTML;
	}
// END PERM CHECK

?>