{*
 *
 * Copyright 2004 James Hunt and OIC Group, Inc.
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
 <html>
 <head>
	<title>{$smarty.const.SITE_TITLE}</title>
	<link rel="stylesheet" href="{$smarty.const.THEME_RELATIVE}style.css" />
 </head>
 <body>
 <div align="center" style="font-weight: bold; font-size: 14pt; background-color: #99cc33; color: white;">{$smarty.const.SITE_TITLE}::Login Page</div>
 <div align="center" style="padding-left: 200px; padding-right: 200px; padding-top: 20px; padding-top: 20px; text-align: justify">You must login to view the content you have requested.  Please do so using the form below.  Once you have successfully logged in, you will be taken to the destination page</div>
	<br />
	<table align="center">
		<tr>
			<td align="left">{$output}</td>
		</tr>
	</table>
	<br /><br />
	<div align="center" style="background-color: #99cc33; color: white;">This feature brought to you by the Exponent Development Team</div>
</body>
</html>