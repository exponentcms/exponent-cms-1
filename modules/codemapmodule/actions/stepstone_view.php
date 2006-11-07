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
# $Id: stepstone_view.php,v 1.3 2005/02/19 16:53:34 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

$stepstone = null;
if (isset($_GET['id'])) $stepstone = $db->selectObject("codemap_stepstone","id=".$_GET['id']);

if ($stepstone) {
	$loc = unserialize($stepstone->location_data);
	$stepstone->milestone = $db->selectObject("codemap_milestone","id=".$stepstone->milestone_id);

	$template = new template("codemapmodule","_view_stepstone",$loc);
	$template->assign("stepstone",$stepstone);
	$template->register_permissions("manage_steps",$loc);
	
	$template->output();
} else echo SITE_404_HTML;