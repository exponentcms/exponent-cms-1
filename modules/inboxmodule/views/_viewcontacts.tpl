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
Personal Contacts
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header inbox_header">Name</td>
		<td class="header inbox_header">User Name</td>
		<td class="header inbox_header">Notes</td>
		<td class="header inbox_header"></td>
	</tr>
{foreach from=$contacts item=contact}
	<tr>
		<td valign="top">{$contact->display_name}</td>
		<td valign="top">{$contact->user->username}</td>
		<td valign="top">{$contact->notes}</td>
		<td valign="top">
			<a href="{link action=edit_contact id=$contact->id}">
				<img src="{$smarty.const.ICON_RELATIVE}edit.gif" border="0" title="Edit this Personal Contact" alt="Edit this Personal Contact"/>
			</a>
			<a href="{link action=delete_contact id=$contact->id}" onClick="return confirm('Are you sure you want to delete this Personal Contact?');">
				<img src="{$smarty.const.ICON_RELATIVE}delete.gif" border="0" title="Delete this Personal Contact" alt="Delete this Personal Contact" />
			</a>
			
			<a class="mngmntlink inbox_mngmntlink" href="{link action=ban cid=$contact->id}" title="Ban this user from contacting you" alt="Ban this user from contacting you" onClick="return confirm('Are you sure you want to ban this user?');">Ban</a>
		</td>
	</tr>
{foreachelse}
	<tr>
		<td colspan="4">
		<i>No contacts found</i>
		</td>
	</tr>
{/foreach}
</table>
<a class="mngmntlink inbox_mngmntlink" href="{link action=edit_contact}">Create New Contact</a>

<hr size="1" />
Contact Groups
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header inbox_header">Group Name</td>
		<td class="header inbox_header">Description</td>
		<td class="header inbox_header"></td>
	</tr>
{foreach from=$groups item=group}
	<tr>
		<td valign="top">{$group->name}</td>
		<td valign="top">{$group->description}</td>
		<td valign="top">
			<a href="{link action=edit_list id=$group->id}">
				<img src="{$smarty.const.ICON_RELATIVE}edit.gif" border="0" title="Edit this Contact Group" alt="Edit this Contact Group" />
			</a>
			<a href="{link action=delete_list id=$group->id}" onClick="return confirm('Are you sure you want to delete this Contact Group?');">
				<img src="{$smarty.const.ICON_RELATIVE}delete.gif" border="0" title="Delete this Contact Group" alt="Delete this Contact Group" />
			</a>
		</td>
	</tr>
{foreachelse}
	<tr>
		<td colspan="4">
		<i>No contact groups found</i>
		</td>
	</tr>
{/foreach}
</table>
<a class="mngmntlink inbox_mngmntlink" href="{link action=edit_list}">Create New Group</a>

<hr size="1" />
Banned Contacts
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
			<a href="{link action=unban id=$contact->id}" onClick="return confirm('Are you sure you want to unban this user?');">
				<img src="{$smarty.const.ICON_RELATIVE}delete.gif" border="0" title="Unban this user" alt="Unban this user"/>
			</a>
		</td>
	</tr>
{foreachelse}
	<tr>
		<td colspan="4">
		<i>No banned contacts found</i>
		</td>
	</tr>
{/foreach}
</table>
<a class="mngmntlink inbox_mngmntlink" href="{link action=ban_user}">Ban User</a>
<hr size="1" />
<a class="mngmntlink inbox_mngmntlink" href="{link action=inbox}">Inbox</a>