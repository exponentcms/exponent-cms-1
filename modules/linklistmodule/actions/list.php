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
# $Id: edit.php,v 1.1 2005/03/13 19:17:05 filetreefrog Exp $
##################################################

if (!defined('EXPONENT')) exit('');

	global $db;

	$config = $db->selectObject('linklistmodule_config',"location_data='".serialize($loc)."'");
	if ($config == null) {
		$config->orderby = 'name';
		$config->orderhow = 0; // Ascending
	}

	if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');

	$links = $db->selectObjects('linklist_link',"location_data='".serialize($loc)."'");

	switch ($config->orderhow) {
		case 0:
			usort($links,'exponent_sorting_byNameAscending');
			break;
		case 1:
			usort($links,'exponent_sorting_byNameDescending');
			break;
		case 2:
			shuffle($links);
			break;
	}

	$template = new template('linklistmodule','Default',$loc);
	$template->assign('links',$links);
	$template->register_permissions(
		array('administrate','configure','create','edit','delete'),$loc);

	$template->output();

?>