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
<table width="85" border="0" cellspacing="0" cellpadding="0">
	{foreach from=$sections item=section}
	{if $section->depth == 0}
	<tr>
		<td align="left" valign="top">
			<table width="85" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="84" bgcolor="#FFFFFF" style="padding-left: 5px">
					{if $section->active == 1}
					<a href="?section={$section->id}" class="navlink sidenav">{$section->name}</a>
					{else}
					{$section->name}
					{/if}
					</td>
					<td width="8" align="left" valign="bottom" style="background-color: #fff"><img src="{$smarty.const.THEME_RELATIVE}images/jde_button_rt.gif" width="8" height="8"/></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="8"></td>
	</tr>
	{/if}
	{/foreach}
	<tr><td align="center">
	{if $canManage == 1}
	[ <a class="navlink" href="{$linkbase}manage">manage</a> ]
	{/if}
	</td></tr>
</table>