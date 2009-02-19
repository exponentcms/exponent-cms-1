<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<?php 
	$config = array(
	"reset-fonts-grids"=>true,
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
			<?php exponent_theme_showModule("textmodule","Default","","@basetheme"); ?>
		</div>
	</div>
<?php echo exponent_theme_footerInfo(); ?>
</body>
</html>
