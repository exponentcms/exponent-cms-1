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

if ($user && $user->is_admin) {
	pathos_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);

	$modclass = $_GET['name'];
	
	$template = new template("administrationmodule","_examplecontent",$loc);
	
	$views = array();
	$loc = pathos_core_makeLocation($modclass,"@example");
	foreach (pathos_modules_views($modclass) as $view) {
		$v = null;
		$v->view = $view;
		
		ob_start();
		call_user_func(array($modclass,"show"),$view,$loc,"Example Title");
		$v->content = ob_get_contents();
		ob_end_clean();
		
		$views[] = $v;
	}
	$template->assign("views",$views);
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>