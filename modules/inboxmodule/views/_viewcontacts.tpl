{*
 * Copyright (c) 2004-2005 OIC Group, Inc.
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
<div class="form_title">{$_TR.form_title}</div>
<div class="form_header">{$_TR.form_header}</div>
<br /><br />
<b>{$_TR.lists}</b>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header inbox_header">{$_TR.list_name}</td>
		<td class="header inbox_header">{$_TR.description}</td>
		<td class="header inbox_header"></td>
	</tr>
{foreach from=$groups item=group}
	<tr>
		<td valign="top">{$group->name}</td>
		<td valign="top">{$group->description}</td>
		<td valign="top">
			<a href="{link action=edit_list id=$group->id}">
				<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" border="0" title="{$_TR.alt_editlist}" alt="{$_TR.alt_editlist}" />
			</a>
			<a href="{link action=delete_list id=$group->id}" onClick="return confirm('{$_TR.deletelist_confirm}');">
				<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" border="0" title="{$_TR.alt_deletelist}" alt="{$_TR.alt_deletelist}" />
			</a>
		</td>
	</tr>
{foreachelse}
	<tr>
		<td colspan="4">
		<i>{$_TR.no_lists}</i>
		</td>
	</tr>
{/foreach}
</table>
<a class="mngmntlink inbox_mngmntlink" href="{link action=edit_list}">{$_TR.new_list}</a>

<hr size="1" />
<b>{$_TR.banned}</b>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header inbox_header">{$_TR.banned_name}</td>
		<td class="header inbox_header">{$_TR.banned_username}</td>
		<td class="header inbox_header"></td>
	</tr>
{foreach from=$banned item=contact}
	<tr>
		<td valign="top">{$contact->user->firstname} {$contact->user->lastname}</td>
		<td valign="top">{$contact->user->username}</td>
		<td valign="top">
			<a href="{link action=unban id=$contact->id}" onClick="return confirm('{$_TR.unblock_confirm}');">
				<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" border="0" title="{$_TR.alt_unblock}" alt="{$_TR.alt_unblock}"/>
			</a>
		</td>
	</tr>
{foreachelse}
	<tr>
		<td colspan="4">
		<i>{$_TR.no_banned}</i>
		</td>
	</tr>
{/foreach}
</table>
<a class="mngmntlink inbox_mngmntlink" href="{link action=ban_user}">{$_TR.block_user}</a>
<hr size="1" />
<a class="mngmntlink inbox_mngmntlink" href="{link action=inbox}">{$_TR.back}</a>