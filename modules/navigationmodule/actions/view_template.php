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

if ($user && $user->is_acting_admin == 1) {
	$page = null;
	if (isset($_GET['id'])) {
		$page = $db->selectObject('section_template','id='.intval($_GET['id']));
	}
	
	if ($page) {
		exponent_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);
	
		$template = new template('navigationmodule','_view_template',$loc);
		$template->assign('template',$page);
		$template->assign('subs',navigationmodule::getTemplateHierarchyFlat($page->id));
		
		$template->output();
	} else {
		echo SITE_404_HTML;
	}
} else {
	echo SITE_403_HTML;
}

?>