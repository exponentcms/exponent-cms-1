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
<h3>Contact Information : {$contact->firstname} {$contact->lastname}</h3>
{permissions level=$smarty.const.UILEVEL_NORMAL}
	{if $permissions.edit == 1}
	<a class="mngmntlink addressbook_mngmntlink" href="{link action=edit id= $contact->id}">
		<img border="0" src="{$smarty.const.ICON_RELATIVE}edit.png" title="Edit this Address Contact" alt="Edit this Address Contact" />
	</a>
	{/if}
	{if $permissions.delete == 1}
	<a class="mngmntlink addressbook_mngmntlink" href="{link action=delete id=$contact->id}" onClick="return confirm('Really delete this Address Contact?');">
		<img border="0" src="{$smarty.const.ICON_RELATIVE}delete.png" title="Delete this Address Contact" alt="Delete this Address Contact" />
	</a>
	{/if}
{/permissions}
<hr size='1'/>
<table cellpadding="3" cellspacing="0" border="0" width="100%" />
<tr>
	<td valign="top" width="5%"><b>Full Name</b></td>
	<td valign="top" width="95%">{$contact->firstname} {$contact->lastname}</td>
</tr>
<tr>
	<td valign="top"><b>Address</b></td>
	<td valign="top">{$contact->address1}<br />{if $contact->address2 != ''}{$contact->address2}<br />{/if}{$contact->city}, {$contact->state} {$contact->zip}</td>
</tr>
<tr><td colspan="2"><hr size='1'/></td></tr>
<tr>
	<td valign="top"><b>Email</b></td>
	<td valign="top">{$contact->email|hide_email}</td>
</tr>
<tr>
	<td valign="top"><b>Homepage</b></td>
	<td valign="top"><a href="{$contact->webpage}" target="_blank">{$contact->webpage}</a></td>
</tr>
<tr><td colspan="2"><hr size='1'/></td></tr>
<tr>
	<td valign="top"><b>Phone</b></td>
	<td valign="top">{$contact->phone}</td>
</tr>
<tr>
	<td valign="top"><b>Mobile</b></td>
	<td valign="top">{$contact->cell}</td>
</tr>
<tr>
	<td valign="top"><b>Fax</b></td>
	<td valign="top">{$contact->fax}</td>
</tr>
<tr>
	<td valign="top"><b>Pager</b></td>
	<td valign="top">{$contact->pager}</td>
</tr>
<tr><td colspan="2"><hr size='1'/></td></tr>
<tr>
	<td valign="top"><b>Notes</b></td>
	<td valign="top">{$contact->notes|nl2br}</td>
</tr>
</table>