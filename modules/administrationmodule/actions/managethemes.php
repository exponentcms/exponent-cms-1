<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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

// Part of the Extensions category

if (!defined('PATHOS')) exit('');

if (pathos_permissions_check('extensions',pathos_core_makeLocation('administrationmodule'))) {
	pathos_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);
	$themes = array();
	if (is_readable(BASE.'themes')) {
		$dh = opendir(BASE.'themes');
		while (($file = readdir($dh)) !== false) {
			if (is_readable(BASE."themes/$file/class.php")) {
				include_once(BASE."themes/$file/class.php");
				
				$theme = new $file();
				
				$t = null;
				$t->name = $theme->name();
				$t->description = $theme->description();
				$t->author = $theme->author();
				$t->preview = is_readable(BASE."themes/$file/preview.jpg") ? PATH_RELATIVE."themes/$file/preview.jpg" : PATH_RELATIVE."themes/" . DISPLAY_THEME . "/noprev.jpg";
				$themes[$file] = $t;
			}
		}
	}
	
	$template = new template('administrationmodule','_thememanager',$loc);
	
	$template->assign('themes',$themes);
	
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>