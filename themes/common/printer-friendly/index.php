<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<?php
		$config = array(
			"reset-fonts-grids"=>false,
			"include-common-css"=>true,
			"include-theme-css"=>true
		);
		echo exponent_theme_headerInfo($section,$config);
	?>
	<link rel="stylesheet" href="print.css" type="text/css" media="print" />
</head>
<body class=" yui-skin-sam">
	<div class="printer-button-bar">
		<form><input type="button" value=" Print this page " onclick="window.print();return false;" /></form>	
	</div>
	<?php exponent_theme_main(); ?>
</body>
</html>
