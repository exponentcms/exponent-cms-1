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
	<a class="mngmntlink addressbook_mngmntlink" href="{link action=edit id= $contact->id}">
		<img border="0" src="{$smarty.const.ICON_RELATIVE}edit.gif" title="Edit this Address Contact" alt="Edit this Address Contact" />
	</a>
	<a class="mngmntlink addressbook_mngmntlink" href="{link action=delete id=$contact->id}" onClick="return confirm('Really delete this Address Contact?');">
		<img border="0" src="{$smarty.const.ICON_RELATIVE}delete.gif" title="Delete this Address Contact" alt="Delete this Address Contact" />
	</a>
{/permissions}
<hr size='1'/>
<table cellpadding="3" cellspacing="0" border="0" />
<tr>
	<td valign="top">Full Name</td>
	<td valign="top">{$contact->firstname} {$contact->lastname}</td>
</tr>
<tr>
	<td valign="top">Address</td>
	<td valign="top">{$contact->address1}<br />{$contact->address2}<br />{$contact->city}, {$contact->state} {$contact->zip}</td>
</tr>
<tr><td colspan="2"><hr size='1'/></td></tr>
<tr>
	<td valign="top">Email</td>
	<td valign="top">{$contact->email}</td>
</tr>
<tr>
	<td valign="top">Homepage</td>
	<td valign="top">{$contact->webpage}</td>
</tr>
<tr><td colspan="2"><hr size='1'/></td></tr>
<tr>
	<td valign="top">Phone</td>
	<td valign="top">{$contact->phone}</td>
</tr>
<tr>
	<td valign="top">Mobile</td>
	<td valign="top">{$contact->cell}</td>
</tr>
<tr>
	<td valign="top">Fax</td>
	<td valign="top">{$contact->fax}</td>
</tr>
<tr>
	<td valign="top">Pager</td>
	<td valign="top">{$contact->pager}</td>
</tr>
<tr><td colspan="2"><hr size='1'/></td></tr>
<tr>
	<td valign="top">Notes</td>
	<td valign="top">{$contact->notes}</td>
</tr>
</table>