{*
 * Copyright (c) 2004-2006 OIC Group, Inc.
 * Written and Designed by James Hunt
 *
 * This file is part of Exponent
 *
 * Exponent is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * GPL: http://www.gnu.org/licenses/gpl.txt
 *
 *}
 <html>
 <head>
	<title>{$smarty.const.SITE_TITLE}</title>
	<link rel="stylesheet" href="{$smarty.const.THEME_RELATIVE}style.css" />
 </head>
 <body>
 <div align="center" style="font-weight: bold; font-size: 14pt;">{$smarty.const.SITE_TITLE}::{$_TR.login_page}</div>
	<br />
	<table align="center">
		<tr>
			<td align="left">{$output}</td>
		</tr>
	</table>
</body>
</html>