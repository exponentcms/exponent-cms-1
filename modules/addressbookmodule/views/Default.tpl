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
 {permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
{/if}
{if $permissions.configure == 1}
	<a href="{link action=configure _common=1}" title="{$_TR.alt_configure}" alt="{$_TR.alt_configure}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}configure.png" /></a>
{/if}
{if $permissions.configure == 1}
	<a href="{link action=configure _common=1}" title="Configure this Address Book" alt="Configure this Address Book"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}configure.png" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}
<div class="moduletitle addressbook_moduletitle">{$moduletitle}</div>
<table cellpadding="2" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header addressbook_header">{$_TR.col_name}</td>
		<td class="header addressbook_header">{$_TR.col_email}</td>
		<td class="header addressbook_header">{$_TR.col_phone}</td>
		<td class="header addressbook_header">&nbsp;</td>
	</tr>
{foreach from=$contacts item=contact}
	<tr>
		<td>{$contact->firstname} {$contact->lastname}</td>
		<td>{$contact->email|hide_email}</td>
		<td>{$contact->phone}</td>
		<td>
			{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
				{if $permissions.administrate == true || $contact->permissions.administrate == true}
					<a href="{link action=userperms int=$contact->id _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm_one}" alt="{$_TR.alt_userperm_one}" /></a>&nbsp;
					<a href="{link action=groupperms int=$contact->id _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm_one}" alt="{$_TR.alt_groupperm_one}" /></a>
				{/if}
			{/permissions}
			{permissions level=$smarty.const.UILEVEL_NORMAL}
				{if $permissions.edit == 1 || $contact->permissions.edit == 1}
					<a class="mngmntlink addressbook_mngmntlink" href="{link action=edit id=$contact->id}">
						<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
					</a>
				{/if}
				{if $permissions.delete == 1 || $contact->permissions.delete == 1}
					<a class="mngmntlink addressbook_mngmntlink" href="{link action=delete id=$contact->id}">
						<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
					</a>
				{/if}
			{/permissions}
			<a class="mngmntlink addressbook_mngmntlink" href="{link action=view id=$contact->id}">
				<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}view.png" title="{$_TR.alt_view}" alt="{$_TR.alt_view}" />
			</a>
		</td>
	</tr>
{foreachelse}
	<tr>
		<td colspan="5"><i>{$_TR.no_contacts}</i></td>
	</tr>
{/foreach}
</table>
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.post == 1}
<a class="mngmntlink addressbook_mngmntlink" href="{link action=edit}" title="{$_TR.alt_new}" alt="{$_TR.alt_new}">{$_TR.add_contact}</a>
{/if}
{/permissions}