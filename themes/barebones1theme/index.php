<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Written and Designed by Adam Kessler
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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<?php echo exponent_theme_headerInfo($section); ?>
   	<link rel="stylesheet" title="default" href="<?php echo THEME_RELATIVE; ?>style.css" />
	<link rel="stylesheet" href="<? echo PATH_RELATIVE?>external/yui/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
</head>
<body onload="eXp.initialize();">
<?php exponent_theme_sourceSelectorInfo(); ?>
<div id="doc2" class="yui-t2"> <!-- change class to change preset --> 
   <div id="hd"><img src="<?php echo THEME_RELATIVE; ?>images/barebones_header.jpg" border="0" /></div> 
   <div id="bd"> 
      	<div id="yui-main"> 
         	<div class="yui-b">
			<?php exponent_theme_main() ?>
		</div> 
      	</div> 
      	<div class="yui-b">
		<?php exponent_theme_showModule("navigationmodule","Expanding Hierarchy"); ?>
	</div> 
   </div> 
   <div id="ft"><?php exponent_theme_showModule("containermodule","Default", "", "@footer"); ?></div> 
</div> 
</body>
</html>

