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
# $Id: edit_board.php,v 1.3 2005/02/19 16:42:19 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

$rank = null;
if (isset($_GET['id'])) {
	$rank = $db->selectObject("bb_rank","id=".$_GET['id']);
	if ($rank) {
		$loc = unserialize($rank->location_data);
	}
}

if (exponent_permissions_check("administrate",$loc)) {
	$form = bb_rank::form($rank);
	$form->location($loc);
	$form->meta("action","save_rank");
	
	$template = new template("bbmodule","_form_editRank",$loc);
	$template->assign("form_html",$form->toHTML());
	$template->assign("is_edit",($rank != null ? 1 : 0));
	$template->output();
}

?>
