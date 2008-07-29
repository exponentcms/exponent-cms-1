<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Written and Designed by James Hunt
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################

include_once('../exponent.php');

$i18n = exponent_lang_loadFile('install/popup.php');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title><?php echo $i18n['title']; ?></title>
	<link rel="stylesheet" title="exponent" href="style.css" />
	<link rel="stylesheet" title="exponent" href="page.css" />
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo LANG_CHARSET; ?>" />
	<meta name="Generator" value="Exponent (formerly Exponent) Content Management System" />
</head>
<body>
	<div class="popup_content_area">
		<?php
		
		$page = (isset($_REQUEST['page']) ? $_REQUEST['page'] : '');
		if (is_readable('popups/'.$page.'.php')) include('popups/'.$page.'.php');
		
		?>
	</div>
</body>
</html>