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

include_once("../../../pathos.php");
define("SCRIPT_RELATIVE",PATH_RELATIVE."modules/navigationmodule/actions/");
define("SCRIPT_ABSOLUTE",BASE."modules/navigationmodule/actions/");
define("SCRIPT_FILENAME","edit_page.php");

$id = -1;
if (isset($_GET['sitetemplate_id'])) {
	pathos_sessions_set("sitetemplate_id",$_GET['sitetemplate_id']);
	$id = $_GET['sitetemplate_id'];
} else if (pathos_sessions_isset("sitetemplate_id")) {
	$id = pathos_sessions_get("sitetemplate_id");
}

pathos_sessions_set("themeopt_override",array(
	"src_prefix"=>"@st".$id,
	"ignore_mods"=>array(
		"navigationmodule",
		"loginmodule"
	),
	"mainpage"=>PATH_RELATIVE."modules/navigationmodule/actions/edit_page.php",
	"backlinktext"=>"Back to Template"
));

#define("PREVIEW_READONLY",1);

define("REDIRECTIONPATH","section_template");

include_once(BASE."index.php");

pathos_sessions_unset("themeopt_override");

?>