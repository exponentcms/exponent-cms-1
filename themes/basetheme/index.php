<?php
if (!defined('EXPONENT')) exit('');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<html>
<head>
	<?php 
	$config = array(
    //"reset-fonts-grids" is now deprecated in place of the following config, although some compatibility check are there for reset-fonts-grids as well
	"css-primer"=>array(URL_FULL."external/yui/build/reset-fonts-grids/reset-fonts-grids.css"),
	"xhtml"=>false,
	"include-common-css"=>true,
	"include-theme-css"=>true
	);
	echo exponent_theme_headerInfo($config); 
	?>
</head>
<body>
	<?php exponent_theme_sourceSelectorInfo(); ?>
	<div id="doc" class="yui-t2">
		<div id="hd">
			<h1 id="logo">Exponent CMS</h1>
			<?php exponent_theme_showModule("loginmodule","Expanded"); ?>
			<?php exponent_theme_showModule("navigationmodule","YUI Top Nav","","@top"); ?>
		</div>
		<div id="bd">
			<div class="yui-b">
				<?php exponent_theme_showModule("containermodule","Default","","@left"); ?>			
				<?php exponent_theme_showModule("containermodule","Default","","@right"); ?>
			</div>
			<div id="yui-main">
				<div class="yui-b">
					<div class="yui-g">
						
						<?php exponent_theme_main(); ?>
					</div>
				</div>
			</div>
		</div>
		<div id="ft">
			<?php //exponent_theme_showModule("textmodule","Default","","@basetheme"); ?>
			<?php exponent_theme_showModule("textmodule","Default","","footer"); ?>
		</div>
	</div>
<?php echo exponent_theme_footerInfo(); ?>
</body>
</html>
