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

// PERM CHECK
	$page = null;
	if (isset($_GET['id'])) $page = $db->selectObject("section_template","id=".$_GET['id']);
	
	if ($page) {
		function tmp_deleteLevel($parent) {
			global $db;
			$kids = $db->selectObjects("section_template","parent=$parent");
			foreach ($kids as $kid) {
				tmp_deleteLevel($kid->id);
			}
			$db->delete("section_template","parent=$parent");
		}
	
		if ($user && $user->is_acting_admin) {
			$db->delete("section_template","id=".$page->id);
			if ($page->parent != 0) {
				$db->decrement('section_template','rank',1,'parent='.$page->parent.' AND rank >= '.$page->rank);
			}
			tmp_deleteLevel($page->id);
	
			pathos_flow_redirect();
		} else echo SITE_403_HTML;
	} else echo SITE_404_HTML;
// END PERM CHECK

?>