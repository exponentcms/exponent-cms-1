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
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header contact_header">Name</td>
		<td class="header contact_header">Email</td>
		<td class="header contact_header">Contact Type</td>
		<td class="header contact_header">&nbsp;</td>
	</tr>
{foreach from=$contacts item=c}
	<tr>
		<td>{$c->name}</td>
		<td>{$c->email}</td>
		<td>
			{if $c->user_id != 0}
				User Account
			{else}
				Manually Entered Address
			{/if}
		</td>
		<td>
			<a class="mngmntlink contact_mngmntlink" href="{link action=edit_contact id=$c->id}">
				<img border="0" src="{$smarty.const.ICON_RELATIVE}edit.gif" title="Edit this Contact" alt="Edit this Contact" />
			</a>
			<a class="mngmntlink contact_mngmntlink" href="{link action=delete_contact id=$c->id}" onClick="return confirm('Are you sure you want to delete this Contact?');">
				<img border="0" src="{$smarty.const.ICON_RELATIVE}delete.gif" title="Delete this Contact" alt="Delete this Contact" />
			</a>
		</td>
	</tr>
{foreachelse}
	<tr>
		<td><i>No contacts</i></td>
	</tr>
{/foreach}
</table>
<a class="mngmntlink contact_mngmntlink" href="{link action=edit_contact}">New Contact</a>