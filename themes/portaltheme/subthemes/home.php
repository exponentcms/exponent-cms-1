<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<?php echo pathos_theme_headerInfo($section); ?>
		<link rel="stylesheet" title="default" href="<?php echo THEME_RELATIVE; ?>style.css" />
	</head>
	
	<body onLoad="pathosJSinitialize();">
		<?php pathos_theme_sourceSelectorInfo(); ?>
	<div id="shell" align="center">
			<table style="margin-left:auto; margin-right:auto;" width="775" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td height="72" align="left">
									<img src="<?php echo THEME_RELATIVE;?>images/logo-exponent.jpg" alt="logo" />								</td>
								<td align="right">
								  <?php pathos_theme_showModule("loginmodule","Expanded"); ?>
							</td>
							</tr>
						</table>
						<img src="<?php echo THEME_RELATIVE;?>images/header-rule.gif" alt="header rule" width="781" height="12" />
					</td>
				</tr>
				<tr>
					<td style="padding:4px 0 4px 0;">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>
									<img src="<?php echo THEME_RELATIVE;?>images/lightbulb.jpg" alt="exponent light bulb" width="582" height="139" />								</td>
								<td valign="top" style="padding:0 0 0 5px;"><img src="<?php echo THEME_RELATIVE;?>images/download-stable.gif" width="193" height="66" alt="Download stable release"/> <a href="http://sourceforge.net/project/showfiles.php?group_id=118524&package_id=136680&release_id=406474"><img src="<?php echo THEME_RELATIVE;?>images/download-rc.gif" alt="Download latest release candidate" width="193" height="66" border="0" style="margin:6px 0 0 0" /></a> </td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td height="38" id="toplevelnav">
					  <?php pathos_theme_showModule("navigationmodule","Top Nav"); ?>
					</td>
				</tr>
				<tr>
					<td  style="padding:0 0 7px 0;">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="582" height="35" valign="top">
								<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td id="childlinks">								
	<?php pathos_theme_showModule("navigationmodule","Children Only2"); ?>
</td>
  </tr>
  <tr>
    <td id="bodycontent">
	<?php
	
	pathos_theme_main();
	
	?>
				  </td>
  </tr>
  <tr>
    <td><img src="<?php echo THEME_RELATIVE;?>images/body-bottom.gif" alt="bottom" width="581" height="41"></td>
  </tr>
</table>
								</td>
							  <td valign="top" align="left">
						        <img src="<?php echo THEME_RELATIVE; ?>images/community.gif" alt="community" border="0" usemap="#MapMap">
						       
					          <?php //pathos_theme_showSectionalModule("containermodule","Default","","@right"); ?></td>
							</tr>
					  </table>
					</td>
				</tr>
			</table>
		<div class="siteFooter">
			<img src="<?php echo THEME_RELATIVE;?>images/header-rule.gif" alt="header rule" width="781" height="12" />
		</div>
		</div>
	    <span style="padding: 5px; background-image: url(<?php echo THEME_RELATIVE; ?><?php echo THEME_RELATIVE;?>images/bottombg.gif);">
	    <?php pathos_theme_showModule("textmodule","Footer","","footer"); ?>
	    </span>
		<map name="Map"><area shape="rect" coords="45,101,137,116" href="http://sourceforge.net/forum/?group_id=118524">
</map>
	</body>
</html>
