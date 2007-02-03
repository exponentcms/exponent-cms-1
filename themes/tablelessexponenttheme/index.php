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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
	<?php //echo "index"; 
	echo exponent_theme_headerInfo($section); ?>
	<style type="text/css">
	
		<!--  
			@import url("<?php echo THEME_RELATIVE;?>css/defaults.css");
			@import url("<?php echo THEME_RELATIVE;?>css/header.css");
			@import url("<?php echo THEME_RELATIVE;?>css/login.css");
			@import url("<?php echo THEME_RELATIVE;?>css/edit.css");
			@import url("<?php echo THEME_RELATIVE;?>css/container.css");
			@import url("<?php echo THEME_RELATIVE;?>css/getexp.css");
			@import url("<?php echo THEME_RELATIVE;?>css/simple.css");
			@import url("<?php echo THEME_RELATIVE;?>css/navigation.css");
			@import url("<?php echo THEME_RELATIVE;?>css/css-dropmenu-style.css");
			@import url("<?php echo THEME_RELATIVE;?>css/breadcrumb.css");
			@import url("<?php echo THEME_RELATIVE;?>css/threecolshell.css");
			@import url("<?php echo THEME_RELATIVE;?>css/rightcolumn.css");
			@import url("<?php echo THEME_RELATIVE;?>css/maincontent.css");
			@import url("<?php echo THEME_RELATIVE;?>css/search.css");
			@import url("<?php echo THEME_RELATIVE;?>css/core.css");
			@import url("<?php echo THEME_RELATIVE;?>css/bugreport.css");
			@import url("<?php echo THEME_RELATIVE;?>css/forumlink.css");
			@import url("<?php echo THEME_RELATIVE;?>css/whosonline.css");
			@import url("<?php echo THEME_RELATIVE;?>css/greybox.css");
			@import url("<?php echo THEME_RELATIVE;?>css/leftcolumn.css");
			@import url("<?php echo THEME_RELATIVE;?>css/centercolumn.css");
			@import url("<?php echo THEME_RELATIVE;?>css/news.css");
			@import url("<?php echo THEME_RELATIVE;?>css/postytext.css");
			@import url("<?php echo THEME_RELATIVE;?>css/footer.css");
		-->
	</style>
	
<!--[if IE ]>  

		<style type="text/css" media="screen">
		img, div, h1, * { behavior: url(<?php echo THEME_RELATIVE;?>css/png-opacity.htc);}
		body {behavior: url(<?php echo THEME_RELATIVE;?>css/csshover.htc); font-size:100%;}
		#menu ul li a {height: 1%;}
		</style>
		
<![endif]-->


</head>
<body onload="exponentJSinitialize();">
<?php exponent_theme_sourceSelectorInfo(); ?>

<!-- "shell" is the main site container.  The width is adjustable in default.css -->
<div class="shell">

	<!-- "header" contains the logo and login, adjustable in header.css -->
	<div class="header">
	
			<!-- login styled in login.css -->
			<?php exponent_theme_showModule("loginmodule","Expanded"); ?>
			
			<!-- header logo styled in header.css -->
		    <h1>
			<?php exponent_theme_showModule("previewmodule","Default"); ?>
			<a href="?"><span>Exponent Content Management System.  Content management system made simple.</span></a></h1>
	</div>
	<!-- END header -->
	
	<!-- horizontal dividing line, configurable in header.css -->
	<div class="hrule1">
		<div class="capright"></div> 
		<div class="capleft"></div> 
	</div>
	
	<!-- container, holding the flash piece and the Get Exponent link  -->
	<div class="container2">
		<!-- Get Exponent block styled in getexp.css -->
		<div class="cmsmadesimple" >
			<div class="getexp" >
				<a href="http://sourceforge.net/project/showfiles.php?group_id=118524">
					<span>Get Exponent from Source Forge now!!!</span>
				</a>		
			</div>
		<!--  the "content management" slogan -->
			<div class="spacer">&nbsp;</div>
			<!--  lightbulb hover effect -->
			<div class="lightbulb"></div>
		</div>
		<!-- CLOSE SECOND CONTAINER  -->
	</div>
	<!-- CLOSE FIRsT CONTAINER  -->

	<!-- three line hirzontal rule  -->
	<div class="hrule2">
		&nbsp;
	</div>		

	<!-- Navigation: CSS drop menus  -->
	<?php exponent_theme_showModule("navigationmodule","cssmenu"); ?>
	

 	<!-- Container for all three columns styled by threecolshell.css -->
 	<!-- Container for all three columns styled by threecolshell.css -->
	<div class="threecolshell" >
				<?  //exponent_theme_main(); ?>

		<!-- 
		********************************************************
		Right column container styled by rightcolumn.css 
		********************************************************
		-->
		<div class="rightcolumn">
			<!-- search module styled by search.css  -->
			<?php exponent_theme_showModule("searchmodule","Default"); ?>

			<!-- image link styled by core.css  -->
			<?php exponent_theme_showModule("textmodule","core"); ?>
			
			<!-- image link styled by bugreport.css  -->
			<?php exponent_theme_showModule("textmodule","bugreport"); ?>
			
			<!-- image link styled by forumlink.css  -->
			<?php exponent_theme_showModule("textmodule","forum"); ?>
			
			<!-- "who's online" view of forum module styled in whosonline.css  -->
			<?php exponent_theme_showModule("containermodule","Default","","@right"); ?>
		</div>
		<!-- END RIGHT COLUMN  -->
		  
		<!-- bread crumb styled by forumlink.css  -->
		<!-- Note:  Breadcrumb floats left like the container for the center and left columns  -->
		<?php exponent_theme_showModule("navigationmodule","Breadcrumb"); ?>

		<!--main body top image styled by maincontent.css  -->
		<div class="maincontenttop">
			<span>Exponent CMS: The easiest and most simple content management system on the planet.</span>
		</div>
		
		
		<!-- START CENTER AND LEFT COLUMN  -->
		 <div class="maincontent">
			 <div class="maincontentpadding">
			<? exponent_theme_main(); ?>
			</div>
			<div class="contentfooter">
				<div class="vrulfade"></div>
				<div class="contentbottom"></div>
			</div>
		</div>
	</div>
</div>

<div class="footer">
	<div class="hrule1 footrule"> 
		<div class="capright"></div> 
		<div class="capleft"></div> 
	</div>
	<div class="footinfo">
		<div>
			<a href="http://www.exponentcms.org/">Exponent Content Management System </a>
			&nbsp;&nbsp;&nbsp;<a href="http://www.oicgroup.net">Exponent CMS Copyright&copy; OIC Group, Inc 2006</a>
		</div>
		<div class="poweredbyexponent"></div> 
		<div class="xhtmlcompliant">
			<a href="http://validator.w3.org/check?uri=referer">
			<span>Exponent Content Management System is written with Valid XHTML 1.0 Transitional code</span>
			</a>
		</div> 
	</div>
</div>


</body>
</html>

