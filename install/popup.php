<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
# All Changes as of 6/1/05 Copyright 2005 James Hunt
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# Exponent is distributed in the hope that it
# will be useful, but WITHOUT ANY WARRANTY;
# without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR
# PURPOSE.  See the GNU General Public License
# for more details.
#
# You should have received a copy of the GNU
# General Public License along with Exponent; if
# not, write to:
#
# Free Software Foundation, Inc.,
# 59 Temple Place,
# Suite 330,
# Boston, MA 02111-1307  USA
#
# $Id$
##################################################

include_once('../pathos.php');

$i18n = pathos_lang_loadFile('install/popup.php');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title><?php echo $i18n['title']; ?></title>
	<link rel="stylesheet" title="exponent" href="style.css" />
	<link rel="stylesheet" title="exponent" href="page.css" />
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="Generator" value="Exponent (formerly Pathos) Content Management System" />
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