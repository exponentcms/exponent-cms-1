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
 {permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}"><img border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="Assign user permissions on this Address Book" alt="Assign user permissions on this Address Book" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}"><img border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="Assign group permissions on this Address Book" alt="Assign group permissions on this Address Book" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}
<div class="moduletitle addressbook_moduletitle">{$moduletitle}</div>
<table cellpadding="2" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header addressbook_header">Name</td>
		<td class="header addressbook_header">Email</td>
		<td class="header addressbook_header">Phone</td>
		<td class="header addressbook_header">Notes</td>
		<td class="header addressbook_header">&nbsp;</td>
	</tr>
{foreach from=$contacts item=contact}
	<tr>
		<td>{$contact->firstname} {$contact->lastname}</td>
		<td>{$contact->email}</td>
		<td>{$contact->phone}</td>
		<td>{$contact->notes}</td>
		<td>
			{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
				{if $permissions.administrate == true || $contact->permissions.administrate == true}
					<a href="{link action=userperms int=$contact->id _common=1}"><img border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="Assign user permissions on this Address Contact" alt="Assign user permissions on this Address Contact" /></a>&nbsp;
					<a href="{link action=groupperms int=$contact->id _common=1}"><img border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="Assign group permissions on this Address Contact" alt="Assign group permissions on this Address Contact" /></a>
				{/if}
			{/permissions}
			{permissions level=$smarty.const.UILEVEL_NORMAL}
				{if $permissions.edit == 1 || $contact->permissions.edit == 1}
					<a class="mngmntlink addressbook_mngmntlink" href="{link action=edit id=$contact->id}">
						<img border="0" src="{$smarty.const.ICON_RELATIVE}edit.png" title="Edit this Address Contact" alt="Edit this Address Contact" />
					</a>
				{/if}
				{if $permissions.delete == 1 || $contact->permissions.delete == 1}
					<a class="mngmntlink addressbook_mngmntlink" href="{link action=delete id=$contact->id}">
						<img border="0" src="{$smarty.const.ICON_RELATIVE}delete.png" title="Delete this Address Contact" alt="Delete this Address Contact" />
					</a>
				{/if}
			{/permissions}
			<a class="mngmntlink addressbook_mngmntlink" href="{link action=view id=$contact->id}" title="View this Address Contact" alt="View this Address Contact">
				View
			</a>
		</td>
	</tr>
{foreachelse}
	<tr>
		<td colspan="5"><i>No contacts in address book</i></td>
	</tr>
{/foreach}
</table>
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.post == 1}
<a class="mngmntlink addressbook_mngmntlink" href="{link action=edit}" title="Create a new Address Contact" alt="Create a new Address Contact">Add Contact</a>
{/if}
{/permissions}