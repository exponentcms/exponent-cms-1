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
# $Id$
##################################################

if (!defined('PATHOS')) exit('');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<?php echo pathos_theme_headerInfo($section); ?>
		<link rel="stylesheet" title="default" href="<?php echo THEME_RELATIVE; ?>style.css" />
	</head>
	
	<body onload="pathosJSinitialize()">
	
		<img id="header" src="<?php echo THEME_RELATIVE;?>images/header1.jpg" height="126" width="319" />
		<div id="logo_holder"><img id="logo" src="<?php echo THEME_RELATIVE;?>images/logo.jpg" height="66" width="280" /></div>
		<div id="header_bottom"></div>
		<div id="nav_holder"><?php pathos_theme_showModule("navigationmodule","Top Nav"); ?></div>
	
		<div id="rightbar"><div class="inner"></div></div>
		<div id="leftbar"><div class="inner">
		<?php pathos_theme_showModule("containermodule","Default","","left"); ?>
		<hr size="1" />
		<?php pathos_theme_showModule("loginmodule","Default","Member Login"); ?>
		<hr size="1" />
		<?php pathos_theme_showSectionalModule("containermodule","Default","","@left"); ?>
		</div></div>
	
		<div id="main">
		<?php pathos_theme_sourceSelectorInfo(); ?>
		<?php pathos_theme_main(); ?>
		</div>
	</body>
</html>
