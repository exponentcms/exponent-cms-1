<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<?php 
	$config = array(
	"reset-fonts-grids"=>true,
	"include-common-css"=>true,
	"include-theme-css"=>true
	);
	echo exponent_theme_headerInfo($section,$config); 
	?>
</head>
<body class="yui-skin-exponent" onload="eXp.initialize();">
	<div id="doc" class="yui-t2">
		<div id="hd">
			<?php exponent_theme_showModule("loginmodule","Expanded"); ?>
			<h1 class="logo">Exponent CMS</h1>
			<?php exponent_theme_showModule("navigationmodule","YUI Top Nav","","@top"); ?>
		</div>
		<div id="bd">
			<div class="yui-b">
				<?php exponent_theme_showModule("containermodule","Default","","@left"); ?>			
			</div>
			<div id="yui-main">
				<div class="yui-b">
					<div class="yui-g">
						<?php echo $router->printerFriendlyLink('Printer Friendly') ?>
						<?php exponent_theme_main(); ?>
					</div>
				</div>
			</div>
		</div>
		<div id="ft">
			<?php exponent_theme_showModule("containermodule","Default","","@footer"); ?>
		</div>
	</div>
</body>
</html>
