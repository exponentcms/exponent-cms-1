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
		<title><?php echo ($section->page_title == "" ? SITE_TITLE : $section->page_title); ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<?php echo pathos_theme_metaInfo($section); ?>
		<link rel="stylesheet" title="default" href="<?php echo THEME_RELATIVE; ?>style.css" />
		<style type="text/css">
		/*
			img { behavior: url(external/png-opacity.htc); }
			*/
		</style>
		<script type="text/javascript" src="<?php echo PATH_RELATIVE; ?>pathos.js.php"></script>
	</head>
	
	<body onLoad="pathosJSinitialize();">
		<?php pathos_theme_sourceSelectorInfo(); ?>
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tbody>
				<tr>
					<td width="48"><img width="48" src="<?php echo THEME_RELATIVE;?>images/leftbar_1.gif"></td>
					<td>
						<table cellpadding="0" cellspacing="0" border="0" width="100%">
							<tr>
								<td width="221"><img src="<?php echo THEME_RELATIVE;?>images/middle-logo.jpg" /></td>
								<td width="43"><img src="<?php echo THEME_RELATIVE;?>images/middle-angle.jpg" /></td>
								<td width="100%" style="background-image: url(<?php echo THEME_RELATIVE; ?>images/middle-repeat.jpg);  background-repeat: repeat-x;"></td>
								<td width="360"><img src="<?php echo THEME_RELATIVE;?>images/middle-image.jpg" /></td>
							</tr>
						</table>
					</td>
					<td width="48"><img width="48" src="<?php echo THEME_RELATIVE;?>images/rightbar_1.gif"></td>
				</tr>
				<tr>
					<td style="background-image: url(<?php echo THEME_RELATIVE; ?>images/leftbar_2.gif);"></td>
					<td>
						<div style="padding: 4px; border-bottom: 1px solid grey">
							<?php pathos_theme_showModule("navigationmodule","Top Nav"); ?>
						</div>
						<table width="100%" cellspacing="0">
							<tbody>
								<tr>
									<td valign="top" width="156" style="border-right: 1px solid grey; padding: 4px;">
										<?php pathos_theme_showModule("loginmodule","Default"); ?>
										<?php pathos_theme_showModule("previewmodule","Default"); ?>
										<br /><hr size="1" />
										<?php pathos_theme_showModule("navigationmodule","Children Only"); ?>
										<br /><hr size="1" /><br />
										<?php pathos_theme_showSectionalModule("containermodule","Default","","@left"); ?>
									</td>
									<td valign="top" style="padding: 7px;">
										<?php
										if (!pathos_theme_inAction()) {
											pathos_theme_showModule("navigationmodule","Breadcrumb");
											echo "<br />";
											echo "<br />";
										}
										pathos_theme_showModule('textmodule','Default','','@uid_'.$user->id);
										pathos_theme_main();
										?>
									</td>
									<td style="border-left: 1px solid grey; padding: 4px;" width="156" valign="top">
										<?php pathos_theme_showSectionalModule("containermodule","Default","","@right"); ?>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
					<td style="background-image: url(<?php echo THEME_RELATIVE; ?>images/rightbar_2.gif);"></td>
				</tr>
				<tr>
					<td><img src="<?php echo THEME_RELATIVE;?>images/leftbar_3-1.gif"></td>
					<td style="background-image: url(<?php echo THEME_RELATIVE; ?>images/bottom1.gif);"></td>
					<td><img src="<?php echo THEME_RELATIVE;?>images/rightbar_3-1.gif"></td>
				</tr>
				<tr>
					<td style="background-image: url(<?php echo THEME_RELATIVE; ?>images/leftbar_3bg.gif); background-repeat: repeat-y;"></td>
					<td style="padding: 5px; background-image: url(<?php echo THEME_RELATIVE; ?>images/bottombg.gif);">
						<?php pathos_theme_showModule("textmodule","Footer","","footer"); ?>
					</td>
					<td style="background-image: url(<?php echo THEME_RELATIVE; ?>images/rightbar_3bg.gif); background-repeat: repeat-y;"></td>
				</tr>
			</tbody>
		</table>
	</body>
</html>