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

$list = null;
if (isset($_GET['id'])) {
	$list = $db->selectObject("inbox_contactlist","id=".$_GET['id']);
}

if ($user && ($list == null || $list->owner == $user->id)) {
	if ($list != null) {
		$membs = $db->selectObjects("inbox_contactlist_member","list_id=".$list->id);
		for ($i = 0; $i < count($membs); $i++) {
			$membs[$i] = $membs[$i]->user_id;
		}
		$list->_members = $membs;
	}
	
	$form = inbox_contactlist::form($list);
	$form->meta("module","inboxmodule");
	$form->meta("action","save_list");

	$template = new template("inboxmodule","_form_editGroup",$loc);
	$template->assign("form_html",$form->toHTML());
	$template->output();
}

?>