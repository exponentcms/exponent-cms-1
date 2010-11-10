<?php
if (!defined('EXPONENT')) exit('');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<?php
		$config = array(
			"reset-fonts-grids"=>false,
			"xhtml"=>true,
			"include-common-css"=>true,
			"include-theme-css"=>true
//			"include-printer-css"=>true
		);
		echo exponent_theme_headerInfo($config);
	?>
	<link type="text/css" media="print, screen" rel="stylesheet" href="http://www.harrisonhills.org/exponent/themes/coolwatertheme/printer-friendly/base-styles.css" />
<!--
	<link type="text/css" media="print" rel="stylesheet" href="http://www.harrisonhills.org/exponent/themes/coolwatertheme/printer-friendly/print.css" />
-->
	<style type="text/css" media="print" />
		.printer-button-bar { display: none }
	</style>
</head>
<body>
	<div class="printer-button-bar">
		<form><input type="button" value=" Print this page " onclick="window.print();return false;" /></form>	
	</div>
	<div id="main">
	<?php exponent_theme_main(); ?>
	</div>
	<?php echo exponent_theme_footerInfo(); ?>
</body>
</html>
