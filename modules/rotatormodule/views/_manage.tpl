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
<table cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td class="header rotator_header">Content</td>
		<td class="header rotator_header"></td>
	</tr>
	{foreach from=$items item=item}
	<tr>
		<td valign="top">{$item->text}</td>
		<td valign="top">
			{if $permissions.manage == 1}
			<a href="{link action=edit_item id=$item->id}">
				<img border="0" src="{$smarty.const.ICON_RELATIVE}edit.png" />
			</a>
			<a href="{link action=delete_item id=$item->id}">
				<img border="0" src="{$smarty.const.ICON_RELATIVE}delete.png" />
			</a>
			{/if}
		</td>
	</tr>
	<tr><td colspan="2"><hr size="1" /></td></tr>
	{foreachelse}
		<tr><td colspan="2" align="center"><i>No content has been created</i></td></tr>
	{/foreach}
</table>

{if $permissions.manage == 1}			
<a href="{link action=edit_item}">New Item</a>
{/if}