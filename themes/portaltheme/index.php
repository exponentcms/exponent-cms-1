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
	
	<body onload="pathosJSinitialize();">
		<?php pathos_theme_sourceSelectorInfo(); ?>
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tbody>
				<tr>
					<td width="48"><img width="48" src="<?php echo THEME_RELATIVE;?>images/leftbar_1.gif" alt="" /></td>
					<td>
						<table cellpadding="0" cellspacing="0" border="0" width="100%">
							<tr>
								<td width="221"><img src="<?php echo THEME_RELATIVE;?>images/middle-logo.jpg" alt="" /></td>
								<td width="43"><img src="<?php echo THEME_RELATIVE;?>images/middle-angle.jpg" alt="" /></td>
								<td width="100%" style="background-image: url(<?php echo THEME_RELATIVE; ?>images/middle-repeat.jpg);  background-repeat: repeat-x;"></td>
								<td width="360"><img src="<?php echo THEME_RELATIVE;?>images/middle-image.jpg" alt="" /></td>
							</tr>
						</table>
					</td>
					<td width="48"><img width="48" src="<?php echo THEME_RELATIVE;?>images/rightbar_1.gif" alt="" /></td>
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
					<td><img src="<?php echo THEME_RELATIVE;?>images/leftbar_3-1.gif" alt="" /></td>
					<td style="background-image: url(<?php echo THEME_RELATIVE; ?>images/bottom1.gif);"></td>
					<td><img src="<?php echo THEME_RELATIVE;?>images/rightbar_3-1.gif" alt="" /></td>
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
