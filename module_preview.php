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

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title><?php echo SITE_TITLE; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="Generator" value="Exponent Content Management System" />
		<link rel="stylesheet" title="default" href="<?php echo THEME_RELATIVE; ?>style.css" />
		<script type="text/javascript" src="<?php echo PATH_RELATIVE; ?>pathos.js.php"></script>
	</head>
	
	<body>
	<?php
	/* exdoc
	 * Define the Preview Readonly file, so that stuff works still.
	 * @node General
	 */
	define('PREVIEW_READONLY',1);
	
	$module = $_GET['module'];
	$view = $_GET['view'];
	$mod = new $module();
	$title = $_GET['title'];
	
	$source = (isset($_GET['source']) ? $_GET['source'] : '@example');
	$loc = pathos_core_makeLocation($module,$source,'');
	$mod->show($view,$loc,$title);
	?>
	<script type="text/javascript">
	var elems = document.getElementsByTagName("a");
	for (var i = 0; i < elems.length; i++) {
		elems[i].setAttribute("onClick","return false;");
	}
	
	elems = document.getElementsByTagName("input");
	for (var i = 0; i < elems.length; i++) {
		if (elems[i].type == "submit") elems[i].setAttribute("disabled","disabled");
	}
	
	elems = document.getElementsByTagName("button");
	for (var i = 0; i < elems.length; i++) {
		elems[i].setAttribute("disabled","disabled");
	}
	</script>
	</body>
</html>
