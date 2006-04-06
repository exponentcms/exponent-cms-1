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

$list = null;
if (isset($_GET['id'])) {
	$list = $db->selectObject('inbox_contactlist','id='.intval($_GET['id']));
}

if ($user && ($list == null || $list->owner == $user->id)) {
	if ($list != null) {
		$membs = $db->selectObjects('inbox_contactlist_member','list_id='.$list->id);
		for ($i = 0; $i < count($membs); $i++) {
			$membs[$i] = $membs[$i]->user_id;
		}
		$list->_members = $membs;
	}
	
	$form = inbox_contactlist::form($list);
	$form->meta('module','inboxmodule');
	$form->meta('action','save_list');

	$template = new template('inboxmodule','_form_editGroup',$loc);
	$template->assign('form_html',$form->toHTML());
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>