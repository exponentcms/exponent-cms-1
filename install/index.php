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

if (!file_exists('not_configured')) {
	exit('This Exponent Site has already been configured.');
}

if (isset($_POST['c'])) {
	define("DB_BACKEND",$_POST['c']['db_engine']);
}


define("SCRIPT_EXP_RELATIVE","install/");
define("SCRIPT_FILENAME","index.php");

include_once("../pathos.php");


if (!isset($_REQUEST['page'])) {
	$_REQUEST['page'] = 'welcome';
}
$page = $_REQUEST['page'];

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
	<style type="text/css">
		body {
			background-color: #CCC;
			margin: 0px;
			padding: 2em;
		}
		
		td.step {
			border-bottom: 1px solid black;
			background-color: #dedede;
			padding: 3px;
			padding-left: 1.5em;
			font-size: 10pt;
			font-family: sans-serif;
			font-weight: bold;
			color: black;
		}
		td.completed {
			background-color: #9c9c9c;
			color: white;
		}
		
		td.header {
			font-family: sans-serif;
			font-size: 32px;
			line-height: 14px;
			color: white;
			padding: 0px;
			margin: 0px;
			padding-right: 0.5em;
		}
		
		td.bar {
			background-color: #7a85a6;
		}
	</style>
</head>
<body>
	<table cellspacing="0" cellpadding="0" border="0" style="border: 1px solid black;" rules="all" width="100%" height="100%">
	<tr>
		<td colspan="2" align="right" class="header bar" height="40px">exponent installer</td>
	</tr>
	<tr>
		<td width="200" class="bar" valign="top">
			<?php include('nav.php'); ?>
		</td>
		<td style="background-color: white; padding: 1em; font-family: sans-serif;" valign="top">
		<?php
		if (file_exists("pages/$page.php")) include("pages/$page.php");
		else echo "Unknown installer page ($page)<br />";
		//GREP:HARDCODEDTEXT
		?>
		</td>
	</tr>
	</table>
</body>
</html>