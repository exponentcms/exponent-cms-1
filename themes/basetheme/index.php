<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">




<html>
<head>
	<?php 
	echo exponent_theme_headerInfo($section); 
	exponent_theme_resetCSS();
	exponent_theme_loadExpDefaults();
	includeCSSFiles();
	?>
	
	<script type="text/javascript" src="<?php echo PATH_RELATIVE; ?>external/yui/build/yahoo/yahoo-min.js"></script>
     <script type="text/javascript" src="<?php echo PATH_RELATIVE; ?>external/yui/build/utilities/util.js"></script>
	<script type="text/javascript" src="<?php echo PATH_RELATIVE; ?>external/yui/build/yahoo-dom-event/yahoo-dom-event.js"></script>
     <script type="text/javascript" src="<?php echo PATH_RELATIVE; ?>external/yui/build/container/container_core.js"></script>

     <script type="text/javascript" src="<?php echo PATH_RELATIVE; ?>external/yui/build/menu/menu.js"></script>

    
</head>
<body>
	<?php exponent_theme_showModule("administrationmodule","Toolbar","","@left"); ?>
<div id="doc" class="yui-t2">

	<div id="hd">
		<h1>Exponent CMS</h1>
	</div>
	<div id="bd">
		<div class="yui-b">
		<?php exponent_theme_showModule("containermodule","Default","","@left"); ?>
		</div>
		<div id="yui-main">
			<div class="yui-b">
				<div class="yui-g">
				<? exponent_theme_main(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="ft">
		Exponent
	</div>
</div>
</body>
</html>
