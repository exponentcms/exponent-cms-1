{*
 *
 * Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
 *
 * This file is part of Exponent
 *
 * Exponent is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * Exponent is distributed in the hope that it
 * will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR
 * PURPOSE.  See the GNU General Public License
 * for more details.
 *
 * You should have received a copy of the GNU
 * General Public License along with Exponent; if
 * not, write to:
 *
 * Free Software Foundation, Inc.,
 * 59 Temple Place,
 * Suite 330,
 * Boston, MA 02111-1307  USA
 *
 * $Id$
 *}
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<head>
		<title>{$smarty.const.SITE_TITLE}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="Generator" value="Exponent Content Management System" />
		<link rel="stylesheet" title="default" href="{$smarty.const.THEME_RELATIVE}style.css" />
		<script type="text/javascript" src="{$smarty.const.PATH_RELATIVE}pathos.js.php"></script>
		<style type="text/css">
		{literal}
			body {
				background-color: #fff;
				background-image: none;
				margin: 1.5em;
				padding: 0px;
			}
		</style>
		<style type="text/css" media="print">
			div.printer_link {
				display: none;
			}
		</style>
		{/literal}
	</head>
	<body onLoad="pathosJSinitialize()">
	<div class="printer_link">
		<a href="#" onClick="print(); return false;">{$_TR.print}</a>
		<br />
	</div>
	{$output}
	
	</body>
</html>