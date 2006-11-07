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
# $Id: stepstone_edit.php,v 1.3 2005/02/19 16:53:34 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

$stepstone = null;
if (isset($_GET['id'])) $stepstone = $db->selectObject("codemap_stepstone","id=".$_GET['id']);
if ($stepstone) {
	$loc = unserialize($stepstone->location_data);
}

if (exponent_permissions_check("manage_steps",$loc)) {
	$form = codemap_stepstone::form($stepstone);
	
	$list = array();
	foreach ($db->selectObjects("codemap_milestone","location_data='".serialize($loc)."'") as $m) {
		$list[$m->id] = $m->name;
	}
	uasort($list,"strnatcmp");
	
	$form->registerBefore("submit","milestone_id","Target Milestone",new dropdowncontrol($stepstone ? $stepstone->milestone_id : 0,$list));
	
	$form->location($loc);
	$form->meta("action","stepstone_save");
	
	$template = new template("codemapmodule","_form_editStepstone");
	$template->assign("is_edit",($stepstone == null ? 0 : 1));
	$template->assign("form_html",$form->toHTML());
	$template->output();
}

?>