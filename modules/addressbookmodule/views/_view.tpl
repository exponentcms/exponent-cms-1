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
<h3>{$_TR.contact_info} : {$contact->firstname} {$contact->lastname}</h3>
{permissions level=$smarty.const.UILEVEL_NORMAL}
	{if $permissions.edit == 1}
	<a class="mngmntlink addressbook_mngmntlink" href="{link action=edit id= $contact->id}">
		<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
	</a>
	{/if}
	{if $permissions.delete == 1}
	<a class="mngmntlink addressbook_mngmntlink" href="{link action=delete id=$contact->id}" onclick="return confirm('{$_TR.delete_confirm}');">
		<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
	</a>
	{/if}
{/permissions}
<hr size='1'/>
<table cellpadding="3" cellspacing="0" border="0" width="100%" />
<tr>
	<td valign="top" width="5%"><b>{$_TR.full_name}</b></td>
	<td valign="top" width="95%">{$contact->firstname} {$contact->lastname}</td>
</tr>
<tr>
	<td valign="top"><b>{$_TR.address}</b></td>
	<td valign="top">{$contact->address1}<br />{if $contact->address2 != ''}{$contact->address2}<br />{/if}{$contact->city}, {$contact->state} {$contact->zip}</td>
</tr>
<tr><td colspan="2"><hr size='1'/></td></tr>
<tr>
	<td valign="top"><b>{$_TR.email}</b></td>
	<td valign="top">{$contact->email|hide_email}</td>
</tr>
<tr>
	<td valign="top"><b>{$_TR.homepage}</b></td>
	<td valign="top"><a href="{$contact->webpage}" target="_blank">{$contact->webpage}</a></td>
</tr>
<tr><td colspan="2"><hr size='1'/></td></tr>
<tr>
	<td valign="top"><b>{$_TR.phone}</b></td>
	<td valign="top">{$contact->phone}</td>
</tr>
<tr>
	<td valign="top"><b>{$_TR.cell}</b></td>
	<td valign="top">{$contact->cell}</td>
</tr>
<tr>
	<td valign="top"><b>{$_TR.fax}</b></td>
	<td valign="top">{$contact->fax}</td>
</tr>
<tr>
	<td valign="top"><b>{$_TR.pager}</b></td>
	<td valign="top">{$contact->pager}</td>
</tr>
<tr><td colspan="2"><hr size='1'/></td></tr>
<tr>
	<td valign="top"><b>{$_TR.notes}</b></td>
	<td valign="top">{$contact->notes|nl2br}</td>
</tr>
</table>