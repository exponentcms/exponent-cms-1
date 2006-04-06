<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
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
		<?php echo exponent_theme_headerInfo($section); ?>
		<link rel="stylesheet" title="default" href="<?php echo THEME_RELATIVE; ?>style.css" />
	</head>
	
	<body onload="exponentJSinitialize();">
		<?php exponent_theme_sourceSelectorInfo(); ?>
		<div align="center">
			<table width="711" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top">
						<table width="710" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="505" align="left" valign="top">
									<a href="?section=1"><img src="<?php echo THEME_RELATIVE; ?>images/jde_header_lft.gif" width="505" height="45" border="0"/ ></a>
								</td>
								<td width="205" align="left" valign="top" background="<?php echo THEME_RELATIVE; ?>images/jde_header_rt.gif" width="206" height="45">
								</td> 
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="left" valign="top">
						<table width="711" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td height="81"><img src="<?php echo THEME_RELATIVE; ?>images/jde_leftnav_lft_top.gif"/></td>
											
								<td rowspan="3" width="108" align="left" valign="top"  background="<?php echo THEME_RELATIVE; ?>images/jde_leftnav_back.gif">
									<table width="98" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td width="98" align="left" valign="top">
												<table width="99" height="190" border="0" cellpadding="0" cellspacing="0">
													<tr>
														<td height="5" align="left" valign="top"><img src="<?php echo THEME_RELATIVE; ?>images/jde_leftnav_top.gif" width="99" height="5"></td>
													</tr>
													<tr>
														<td align="left" valign="top">
														<br /><br />
															<?php exponent_theme_showModule("navigationmodule","Side Navigation"); ?>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
								<td rowspan="3" width="269" align="left" valign="top"><img src="<?php echo THEME_RELATIVE; ?>images/jde_image_lft.jpg" width="269" height="190"></td>
								<td rowspan="3" bgcolor="#cccccc"><img src="<?php echo THEME_RELATIVE;?>images/spacer.gif" width="5" /></td>
								<td rowspan="3" width="12" valign="bottom"><img src="<?php echo THEME_RELATIVE; ?>images/jde_radiusblft.gif" /></td>
								<td rowspan="3" width="247" align="left" valign="top" style="padding: 5px">
								<img src="<?php echo THEME_RELATIVE; ?>images/spacer.gif" width="278" height="1">
								<?php exponent_theme_showModule("textmodule","Default","","welcometext"); ?>
								</td>
								<td rowspan="3" background="<?php echo THEME_RELATIVE; ?>images/jde_image_area_rt.gif" style="background-repeat: repeat-y; border-top: 1px solid #000; border-bottom: 1px solid #000;" width="29"><img src="<?php echo THEME_RELATIVE; ?>images/jde_image_area_rt.gif"/></td>
							</tr>
							
							</tr><tr>
							<td style="background-image: url(<?php echo THEME_RELATIVE; ?>images/jde_leftnav_lft_fill.gif); background-position: left; background-repeat: repeat-y;">&nbsp;</td>
							</tr><tr>
							<td height="85" valign="bottom"><img src="<?php echo THEME_RELATIVE; ?>images/jde_leftnav_lft_bot.gif"/></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="left" valign="top">
						<table width="711" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="515" align="left" valign="top">
									<table width="515" border="0" cellspacing="0" cellpadding="0">
										<tr>
											      <td width="515" align="left" valign="top"><img src="<?php echo THEME_RELATIVE; ?>images/jde_mid_lft.gif" width="515" height="32"></td>
										</tr>
										<tr>
											<td align="left" valign="top">
												<table width="515" border="0" cellspacing="0" cellpadding="0">
													<tr>
														<td width="76" height="183" align="left" valign="top"><img src="<?php echo THEME_RELATIVE; ?>images/jde_textarea_lft.gif" width="76" height="151"></td>
														<td width="6" align="left" valign="top" bgcolor="#CDCCCC">
															<p><img src="<?php echo THEME_RELATIVE; ?>images/jde_textarea_sliver.gif" width="6" height="151"></p>
															<p>&nbsp;</p>
														</td>
														<td width="431" align="center" valign="top" bgcolor="#336699">
															<table width="400" border="0" cellspacing="0" cellpadding="0">
																<tr>
																	<td align="left" valign="top">
																		<?php exponent_theme_main(); ?>
																	</td>
																</tr>
															</table>
														</td>
														<td width="8" align="left" valign="top" bgcolor="#CDCCCC">&nbsp;</td>
													</tr>
													
													<tr>
														<td width="76" height="1" align="left" valign="top">&nbsp;</td>
														<td width="6" align="left" valign="top" bgcolor="#CDCCCC">
															<p>&nbsp;</p>
														</td>
														<td width="431" align="center" valign="top" bgcolor="#336699">
															<br />
															<table border="0" width="400" border="0" cellspacing="0" cellpadding="0">
																<tr>
																	<td align="left" valign="top" style="border-top: 1px dashed #CDCCCC;">
																		<br />
																		<?php exponent_theme_showModule("textmodule","Default","","footer"); ?>
																	</td>
																</tr>
															</table>
														</td>
														<td width="8" align="left" valign="top" bgcolor="#CDCCCC">&nbsp;</td>
													</tr>
												</table>
											</td>
										</tr>
										<tr>
											<td align="right" valign="top"><img src="<?php echo THEME_RELATIVE; ?>images/jde_textarea_bot.gif" width="440" height="26"></td>
										</tr>
									</table>
								</td>
								<td width="196" align="left" valign="top">
									<table width="195" border="0" cellspacing="0" cellpadding="0">
										<tr align="left" valign="top">
											<td colspan="3"><img src="<?php echo THEME_RELATIVE; ?>images/jde_leftarea_top.gif" width="195" height="33"></td>
										</tr>
										<tr>
											<td colspan="3">&nbsp;</td>
										</tr>
										<tr>
											<td width="22" rowspan="5" align="left" valign="top">&nbsp;</td>
											<td width="186" align="left" valign="top">
												<?php exponent_theme_showModule("loginmodule","Default"); ?>
												<?php exponent_theme_showModule("previewmodule","Default"); ?>
											</td>
											<td width="1" rowspan="5">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3"><hr size="1" /></td>
										</tr>
										<tr>
											<td width="186" align="left" valign="top">
												<?php exponent_theme_showModule("navigationmodule","Children Only"); ?>
											</td>
										</tr>
										
										<tr>
											<td><hr size="1" /></td>
										</tr>
										<tr>
											<td align="left" valign="top">
												<?php exponent_theme_showSectionalModule("containermodule","Default","","@right"); ?>
											</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="left" valign="top">&nbsp;</td>
				</tr>
			</table>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
		</div>
	</body>
</html>
