{*
 *
 * Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
 * All Changes as of 6/1/05 Copyright 2005 James Hunt
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
	<title>{$_TR.module_information}</title>
	<link rel="stylesheet" href="{$smarty.const.THEME_RELATIVE}style.css" />
</head>
<body>
<br /><br />
<div align="center" style="font-weight: bold">{if $name == ''}{$_TR.unknown_module}{else}{$name}{/if}</div>
<br />

<div style="border-top: 3px dashed lightgrey; padding: 3px;">
<table cellpadding="0" cellspacing="0" border="0">
{if $is_orphan}
<tr>
	<td>{$_TR.archived_module}</td>
</tr>
{else}
<tr>
	<td>{$_TR.view}:&nbsp;</td>
	<td>{$container->view}</td>
</tr>
<tr>
	<td>{$_TR.title}:&nbsp;</td>
	<td>{if $container->title == ""}<i>&lt;{$_TR.none}&gt;</i>{else}{$container->title}{/if}</td>
</tr>
{/if}
</table>
</div>

<div style="border-top: 3px dashed lightgrey; padding: 3px;">{if $name == ''}<i>{$_TR.module_not_found}</i>{elseif $info == ''}<i>{$_TR.no_description}</i>{else}{$info|nl2br}{/if}</div>
</body>
</html>