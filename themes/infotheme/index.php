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

if (!defined('PATHOS')) exit('');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<?php echo pathos_theme_headerInfo($section); ?>
		<link href="<?php echo THEME_RELATIVE; ?>cc.css" rel="stylesheet" type="text/css">
	</head>
<body background="<?php echo THEME_RELATIVE; ?>images/cc_back.jpg" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="pathosJSinitialize()">
	<?php pathos_theme_sourceSelectorInfo(); ?>
	<table width="100%" height="632" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td height="121" align="left" valign="bottom"><img src="<?php echo THEME_RELATIVE; ?>images/cc_name.gif" width="505" height="41"></td>
		</tr>
		<tr>
			<td height="8" background="<?php echo THEME_RELATIVE; ?>images/cc_slim_back2.gif"></td>    
		</tr>
		<tr>
			<td height="271" align="left" valign="top" background="<?php echo THEME_RELATIVE; ?>images/cc_mid_back.gif" bgcolor="CBB061">
				<table width="655" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="101" rowspan="2" align="left" valign="top" bgcolor="666633"><img src="<?php echo THEME_RELATIVE; ?>images/cc_left.jpg" width="101" height="255"></td>
						<td height="33" colspan="2" align="left" valign="top">
							<table width="541" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="10">&nbsp;</td>
									<td width="526" height="27" bgcolor="CBB061">
										<?php pathos_theme_showModule("navigationmodule","Top Nav"); ?>
									</td>
								</tr>
								<tr align="left" valign="top">
									<td height="5" colspan="2"><img src="<?php echo THEME_RELATIVE; ?>images/cc_keyline_top.gif" width="555" height="5"></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<?php
						if (pathos_theme_inAction()) {
							?><td width="554" height="239" colspan="2" valign="top" style="padding: 10px"><?php
							pathos_theme_runAction();
						} else {
							?><td width="180" height="239" align="left" valign="top">
								<table width="180" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td colspan="3">&nbsp;</td>
									</tr>
									<tr>
										<td height="221">&nbsp;</td>
										<td width="160" align="left" valign="top" class="footer">
									<?php pathos_theme_showModule("loginmodule","Default"); ?>
									<?php pathos_theme_showModule("previewmodule","Default"); ?>
									<br /><hr size="1"/><br /><?php
								pathos_theme_showModule("navigationmodule","Children Only");
								?><br /><hr size="1"/><br /><?php
								pathos_theme_showSectionalModule("containermodule","Default","","@left");
								?><br /><hr size="1"/><br /><?php
								pathos_theme_showSectionalModule("containermodule","Default","","@right");
								?></td>
										<td>&nbsp;</td>
									</tr>
								</table>
							</td>
							<td width="374" align="center" valign="top">
							
								<table width="374" border="0" cellspacing="5" cellpadding="0">
									<tr>
										<td align="left" valign="top">
									<?php
									pathos_theme_showModule("textmodule","Default","","Header Text");
									pathos_theme_mainContainer();
							?></td>
								</tr>
							</table>
						</td><?php
						}
						?>
					</tr>
				</table>
			</tr>
			<tr>
				<td height="25" align="left" valign="top" style="background-image: url(<?php echo THEME_RELATIVE; ?>images/cc_bot_back2.gif); background-repeat: repeat-x" bgcolor="CBB061">
					<table width="750" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="101" height="25" bgcolor="666633">&nbsp;</td>
							<td width="180" bgcolor="CBB061">&nbsp;</td>
							<td width="1" height="18" bgcolor="999966" align="left" valign="top"></td>
							<td width="468" align="left" valign="middle">
								<table width="374" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td width="7">&nbsp;</td>
										<td class="footer">
										<?php pathos_theme_showModule("textmodule","Default","","footer"); ?>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td height="8" background="<?php echo THEME_RELATIVE; ?>images/cc_slim_back.gif"></td>
			</tr>
			<tr>
				<td height="199" class="caption"></td>
			</tr>
	</table>
</body>
</html>
