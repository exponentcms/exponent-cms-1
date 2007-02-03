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
<?php echo exponent_theme_headerInfo($section); ?>
<script type="text/JavaScript" src="<?php echo THEME_RELATIVE; ?>swapimage.js"></script>
<link rel="shortcut icon" href="/themes/swportaltheme/images/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" title="default" href="<?php echo THEME_RELATIVE; ?>style.css" />
<link rel="stylesheet" title="default" href="<?php echo THEME_RELATIVE; ?>textmodule.css" />
<link rel="stylesheet" title="default" href="<?php echo THEME_RELATIVE; ?>css-dropmenu-style.css" />
<!--[if IE]>
		<style type="text/css" media="screen">
		body {behavior: url(/themes/blackportaltheme/csshover.htc); font-size:100%;}
		#menu ul li a {height: 1%;}
		</style>
		<![endif]-->
</head>
<body onLoad="exponentJSinitialize();MM_preloadImages('images/lightbulb-h.jpg')">
<?php exponent_theme_sourceSelectorInfo(); ?>
<div id="shell" align="center">
  <table style="margin-left:auto; margin-right:auto;" width="780" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td>
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="71" align="left"><img src="<?php echo THEME_RELATIVE;?>images/logo-exponent.jpg" alt="logo" /> </td>
            <td align="right"><?php exponent_theme_showModule("loginmodule","Expanded"); ?>
            </td>
          </tr>
        </table>
       <div style="background: url(<?php echo THEME_RELATIVE;?>images/headerbar_rep.gif)"> <img src="<?php echo THEME_RELATIVE;?>images/headerbar_rightcap.gif" alt="header rule" style="float:right"/><img src="<?php echo THEME_RELATIVE;?>images/headerbar_leftcap.gif" alt="header rule"/></div> </td>
    </tr>
    <tr>
      <td style="padding:4px 0 4px 0;">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td style=" padding:0 2px 0 0;" nowrap="nowrap">
				<div id="lightbulb" style="width:100%">

				<div id="bluepaneldoc" style="float:right">
				</div>

			<div class="headerimage" style="background:url(<?php echo THEME_RELATIVE;?>images/headerImageRep.gif);">
			<a style="float:right" class="imagebutton" href="index.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('lightbulb','','<?php echo THEME_RELATIVE;?>images/lightbulb-h.jpg',1)"><img src="<?php echo THEME_RELATIVE;?>images/lightbulb.jpg" alt="simple!" name="lightbulb" width="330" height="138" border="0"></a><div style="background:url(<?php echo THEME_RELATIVE;?>images/cmsmadesimple.jpg) no-repeat ; height:138px">&nbsp;</div>
			</div>
			</div>



			<!-- <a class="imagebutton" href="http://sourceforge.net/project/showfiles.php?group_id=118524&package_id=136680&release_id=406474"><img src="<?php // echo THEME_RELATIVE;?>images/download-stable.jpg" alt="download exponent" width="195" height="138" border="0"></a>
			-->
			</td>
            <td style=" padding:0 0px 0 0"></td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td nowrap height="38" id="toplevelnav"><?php exponent_theme_showModule("navigationmodule","CSS Menu"); ?></div>
      </td>
    </tr>
    <tr>
      <td  style="padding:0 0 7px 0;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="582" height="35" valign="top"><table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td id="bodytop"></td>
                </tr>
                <tr>
                  <td id="bodycontentall">
				  <div id="breadcrumb"><?php exponent_theme_showModule("navigationmodule","Breadcrumb"); ?></div>
				  <?php	exponent_theme_main();?>
                  </td>
                </tr>
                <tr>
                  <td><div align="right"><img src="<?php echo THEME_RELATIVE;?>images/body-bottom-nd.gif" alt="bottom" width="581" height="41"></div></td>
                </tr>
              </table></td>
            <td valign="top" align="left"><div class="sidebar" style="margin-left:7px;">

								<?php exponent_theme_showModule("navigationmodule","Children Only"); ?>
								<?php exponent_theme_showModule("previewmodule","Default");	?>
								<?php //exponent_theme_showModule("searchmodule","Default"); ?>
                <?php exponent_theme_showSectionalModule("containermodule","Default","","@rightsidebar"); ?>
              </div></td>
          </tr>
        </table></td>
    </tr>

  </table>
  <div class="siteFooter"><img src="<?php echo THEME_RELATIVE;?>images/header-rule.gif" alt="header rule" width="781" height="12" />
<?php exponent_theme_showModule("textmodule","Footer","","footer"); ?>
   </div>
</div>
<span style="padding: 5px; background-image: url(<?php echo THEME_RELATIVE; ?><?php echo THEME_RELATIVE;?>images/bottombg.gif);">
</span>
<map name="Map">
  <area shape="rect" coords="45,101,137,116" href="http://sourceforge.net/forum/?group_id=118524">
</map>
</body>
</html>