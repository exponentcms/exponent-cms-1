<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>My New Exponent Site</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="Generator" content="Exponent Content Management System - %%MAJOR%%.%%MINOR%%.%%REVISION%%.%%TYPE%%" />
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<!--[if IE 6]><style type="text/css"> img { behavior: url(external/png-opacity.htc); } body { behavior: url(external/csshover.htc); }</style><![endif]-->

	<link rel="stylesheet" type="text/css" href="http://localhost/trunk/exponent/tmp/css/exp-styles-min.css">
	<script type="text/javascript" src="http://localhost/trunk/exponent/exponent.js.php"></script>
	<script type="text/javascript" src="external/yui/build/yahoo-dom-event/yahoo-dom-event.js"></script>
	<script type="text/javascript" src="external/yui/build/animation/animation-min.js"></script>
	<script type="text/javascript" src="external/yui/build/dragdrop/dragdrop-min.js"></script>
	<script type="text/javascript" src="external/yui/build/container/container-min.js"></script>

	<script type="text/javascript" src="external/yui/build/container/container_core-min.js"></script>
	<script type="text/javascript" src="external/yui/build/menu/menu-min.js"></script>
	<script type="text/javascript" src="external/yui/build/element/element-beta-min.js"></script>
	<script type="text/javascript" src="external/yui/build/tabview/tabview-min.js"></script>
	<script type="text/javascript" src="external/yui/build/connection/connection-min.js"></script>
	<script type="text/javascript" src="external/yui/build/json/json-beta-min.js"></script>
	<script type="text/javascript" src="http://localhost/trunk/exponent/js/exponent.js"></script>
</head>
<body>
<!-- wrap starts here -->
<div id="wrap">
	<!--header -->
	<div id="header">			
		<h1 id="logo-text"><a href="<?php echo URL_FULL; ?>index.php">ex<span class="green">ponent</span> <sup>CMS</sup></a></h1>		
		<p id="slogan">The "coolwater" theme from Styleshout.com</p>		
		<div id="header-links">
			<?php exponent_theme_showModule("textmodule","Top Links"); ?>
		</div>		
		<div id="header-login">
			<?php exponent_theme_showModule("loginmodule","Expanded"); ?>
		</div>		
	</div>
	<!-- navigation -->	
	<div  id="menu">
		<?php exponent_theme_showModule("navigationmodule","YUI Top Nav","","@top"); ?>
	</div>
	<!-- content-wrap starts here -->
	<div id="content-wrap">
		
		<div id="main">				
			<?php exponent_theme_main(); ?>
		</div>
		<div id="sidebar">
			<?php exponent_theme_showModule("containermodule","Default","","@left"); ?>			
			<h2>Support Styleshout</h2>
			<p>If you are interested in supporting my work and would like to contribute, you are
			welcome to make a small donation through the 
			<a href="http://www.styleshout.com/">donate link</a> on my website - it will 
			be a great help and will surely be appreciated.</p>
		</div>
	<!-- content-wrap ends here -->	
	</div>
	<!--footer starts here-->
	<div id="footer">
		<?php exponent_theme_showModule("containermodule","Default","","@footer"); ?>				
	</div>	


</div>

</body>
</html>
