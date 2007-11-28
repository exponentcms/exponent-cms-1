<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<?php 
		echo exponent_theme_headerInfo($section); 
		exponent_theme_resetCSS();
		exponent_theme_includeCSSFiles("",true);
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
