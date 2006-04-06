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
<table cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td class="header rotator_header">{$_TR.content}</td>
		<td class="header rotator_header"></td>
	</tr>
	{foreach from=$items item=item}
	<tr>
		<td valign="top">{$item->text}</td>
		<td valign="top">
			{if $permissions.manage == 1}
			<a href="{link action=edit_item id=$item->id}">
				<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}edit.png" />
			</a>
			<a href="{link action=delete_item id=$item->id}">
				<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.png" />
			</a>
			{/if}
		</td>
	</tr>
	<tr><td colspan="2"><hr size="1" /></td></tr>
	{foreachelse}
		<tr><td colspan="2" align="center"><i>{$_TR.no_content}</i></td></tr>
	{/foreach}
</table>

{if $permissions.manage == 1}			
<a href="{link action=edit_item}">{$_TR.new_content}</a>
{/if}