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
<div class="moduletitle inbox_moduletitle">Personal Contacts</div>
<div style="border-top: 1px solid lightgrey; border-bottom: 1px solid lightgrey; padding: 1em;">
Here you can create contact lists and ban users.  Contact lists are like personal mailing lists which allow you to contact an entire group of people using one 'address'.
</div>
<br /><br />
<b>Personal Lists</b>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header inbox_header">List Name</td>
		<td class="header inbox_header">Description</td>
		<td class="header inbox_header"></td>
	</tr>
{foreach from=$groups item=group}
	<tr>
		<td valign="top">{$group->name}</td>
		<td valign="top">{$group->description}</td>
		<td valign="top">
			<a href="{link action=edit_list id=$group->id}">
				<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" border="0" title="Edit this Contact List" alt="Edit this Contact List" />
			</a>
			<a href="{link action=delete_list id=$group->id}" onClick="return confirm('Are you sure you want to delete this Contact List?');">
				<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" border="0" title="Delete this Contact List" alt="Delete this Contact List" />
			</a>
		</td>
	</tr>
{foreachelse}
	<tr>
		<td colspan="4">
		<i>No personal lists found</i>
		</td>
	</tr>
{/foreach}
</table>
<a class="mngmntlink inbox_mngmntlink" href="{link action=edit_list}">Create New List</a>

<hr size="1" />
<b>Blocked Users</b>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header inbox_header">Name</td>
		<td class="header inbox_header">User Name</td>
		<td class="header inbox_header"></td>
	</tr>
{foreach from=$banned item=contact}
	<tr>
		<td valign="top">{$contact->user->firstname} {$contact->user->lastname}</td>
		<td valign="top">{$contact->user->username}</td>
		<td valign="top">
			<a href="{link action=unban id=$contact->id}" onClick="return confirm('Are you sure you want to unblock this user?');">
				<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" border="0" title="Unblock this user" alt="Unblock this user"/>
			</a>
		</td>
	</tr>
{foreachelse}
	<tr>
		<td colspan="4">
		<i>No blocked users found</i>
		</td>
	</tr>
{/foreach}
</table>
<a class="mngmntlink inbox_mngmntlink" href="{link action=ban_user}">Block User</a>
<hr size="1" />
Back to <a class="mngmntlink inbox_mngmntlink" href="{link action=inbox}">Inbox</a>