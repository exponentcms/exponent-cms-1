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
# $Id: save_board.php,v 1.3 2005/02/19 16:42:19 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

$bb = null;
$bbloc = null;
if (isset($_POST['id'])) {
	$bb = $db->selectObject("bb_board","id=".$_POST['id']);
	if ($bb) {
		$loc = unserialize($bb->location_data);
		$bbloc = exponent_core_makeLocation($loc->mod,$loc->src,"b".$bb->id);
	}
}

if (	($bb == null && exponent_permissions_check("create_board",$loc)) ||
	($bb != null && exponent_permissions_check("edit_board",$loc)) ||
	($bb != null && exponent_permissions_check("edit_board",$bbloc))
) {

	$bb = bb_board::update($_POST,$bb);
	$bb->location_data = serialize($loc);
	
	if (isset($bb->id)) $db->updateObject($bb,"bb_board");
	else $db->insertObject($bb,"bb_board");
	
	exponent_flow_redirect();

}

?>