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
	);
	echo exponent_theme_headerInfo($config);
	?>
</head>
<body>
<?php exponent_theme_sourceSelectorInfo(); //this will be deprecated by copy 'n paste in the 0.98 ?>
<!-- wrap starts here -->
<div id="wrap" class="fullbody">
	<!--header -->
	<div id="header">
		<h1 id="logo-text"><a href="<?php echo URL_FULL; ?>index.php">ex<span class="green">ponent</span> <sup>CMS</sup></a></h1>
		<p id="slogan">The "coolwater" theme from Styleshout.com</p>
		<div id="header-links">
			<a href="<?php echo URL_FULL; ?>">Home</a> |
			<a href="<?php echo URL_FULL; ?>contact-form">Contact Us</a> |
			<a href="<?php echo URL_FULL; ?>sitemap">Site-map</a>
		</div>
		<div id="header-login">
			<?php exponent_theme_showModule("loginmodule","Expanded"); ?>
		</div>
	</div>
	<!-- navigation -->
	<div  id="menu">
		<?php exponent_theme_showModule("navigationmodule","YUI Top Nav"); ?>
	</div>
	<!-- content-wrap starts here -->
	<div id="content-wrap">
		<div id="main">
			<?php exponent_theme_main(); ?>
		</div>
	<!-- content-wrap ends here -->
	</div>
	<!--footer starts here-->
	<div id="footer">
		<?php exponent_theme_showModule("textmodule","Default","","footer"); ?>				
	</div>
</div>
<?php
	echo exponent_theme_footerInfo();
?>
</body>
</html>
