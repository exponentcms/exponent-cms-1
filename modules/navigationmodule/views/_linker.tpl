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
		<meta type="Generator" value="Exponent Content Management System" />
		<link rel="stylesheet" href="{$smarty.const.THEME_RELATIVE}style.css" />
		<style type="text/css">
		{literal}
			body {
				padding: 5px;
			}
		{/literal}
		</style>
	</head>
	<body>
	<b>Pick a Section</b><hr size="1" />
		 <table cellpadding="1" cellspacing="0" border="0" width="100%">
		{foreach from=$sections item=section}
		<tr><td style="padding-left: {math equation="x*20" x=$section->depth}px">
		{if $section->active}<a href="{$smarty.get.linkbase}&section={$section->id}&section_name={$section->name|escape:"url"}" class="navlink">{$section->name|escape:"htmlall"}</a>&nbsp;
		{else}{$section->name}
		{/if}
		</td></tr>
		{/foreach}
		</table>
	</body>
</html>