<?php

##################################################
#	
#	KubrickTheme v1.0 for ExponentCMS 0.96.x
#
#	This theme was designed by Michael Heilemann, whose blog you will find at binarybonsai.com.
#	It was ported to Xoops by kavaXtreme who can't be bothered with a proper blog, but has a site at kavaxtreme.radiantchristians.com. and it was ported #	And this version of the theme is ported to ExponentCMS by ergin.altintas.org.
#	
#	The CSS, XHTML and design is released under GPL:
#	http://www.opensource.org/licenses/gpl-license.php
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
<div id="doc" class="yui-t3" style="background: url(<?php echo THEME_RELATIVE; ?>images/kubricksides.png);"> 
   	<div id="hd" style="background: url(<?php echo THEME_RELATIVE; ?>images/kubrickheader.png);height: 195px;padding: 0px 40px 0px 40px;text-align:center">
                        <h1><?php exponent_theme_showModule("navigationmodule","You Are Here"); ?></h1>
                            <?php exponent_theme_showModule("navigationmodule","Top Nav"); ?>
                         <p><?php exponent_theme_showModule("navigationmodule","Children Only"); ?></p>
	</div> 
   <div id="bd" style="padding-left: 15px;padding-right: 10px;">
	 <?php exponent_theme_showModule("navigationmodule","Breadcrumb"); ?> 
    <div id="yui-main"> 
    <div class="yui-b">
	<div class="yui-g" style="padding-left: 10px;padding-right: 10px;"> 
		<?php exponent_theme_main(); ?>
    	</div> 
    </div> 
    </div> 
    	<div class="yui-b" style="padding-left: 10px;">
		<?php exponent_theme_showSectionalModule("containermodule","Default","","@left"); ?>
                <?php exponent_theme_showSectionalModule("containermodule","Default","","@right"); ?>
	</div> 
    </div> 
   <div id="ft" style="margin-left: -15px; background: url(<?php echo THEME_RELATIVE; ?>images/kubrickfooter.png);width: 750px; height: 62px;"></div> 
</div> 
</body> 
</html> 
