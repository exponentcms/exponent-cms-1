<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<?php 
	echo exponent_theme_headerInfo($section); 
	//exponent_theme_resetCSS();
	//exponent_theme_loadYUICSS(array('menu'));
	//exponent_theme_loadExpDefaults();
	exponent_theme_includeCSSFiles();
	exponent_theme_loadYUIJS();
	?>
</head>
<body>
	<?php exponent_theme_showModule("administrationmodule","Toolbar","","@left"); ?>
	<div id="doc" class="yui-t2">
		<div id="hd">
			<?php exponent_theme_showModule("loginmodule","Expanded"); ?>
			<h1>Exponent CMS</h1>
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
			Exponent - Footer
		</div>
	</div>
</body>
</html>
