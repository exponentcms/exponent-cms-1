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

if (isset($_POST['c'])) {
	define("DB_BACKEND",$_POST['c']['db_engine']);
}
include_once("../pathos.php");
define("SCRIPT_RELATIVE",PATH_RELATIVE."install/");
define("SCRIPT_ABSOLUTE",BASE."install/");
define("SCRIPT_FILENAME","install.php");

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Exponent CMS :: Install Wizard</title>
	<link rel="stylesheet" title="exponent" href="style.css" />
	<link rel="stylesheet" title="exponent" href="page.css" />
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="Generator" value="Exponent (formerly Pathos) Content Management System" />
	<script type="text/javascript">
	
	function pop(page) {
		url = "popup.php?page="+page;
		window.open(url,"pop","height=400,width=300,title=no,titlebar=no,scrollbars=yes");
		return false;
	}
	
	</script>
</head>
<body>
	<div align="right" class="logo_installer">
	<img src="images/logo-installer.png" />
	</div>
	
	<div class="content_area">
		<?php
	
	$page = (isset($_REQUEST['page']) ? $_REQUEST['page'] : "sanity");
	if (file_exists("pages/$page.php")) include("pages/$page.php");
	else echo "Unknown installer page ($page)<br />";
	//GREP:HARDCODEDTEXT
	?>
	</div>
</body>
</html>