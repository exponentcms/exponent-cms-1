{*
 * Copyright (c) 2004-2011 OIC Group, Inc.
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

<div class="contactmodule manager">
	<p><a class="mngmntlink contact_mngmntlink" href="{link action=edit_contact}">{$_TR.new_contact}</a></p>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td class="header contact_header">{$_TR.name}</td>
			<td class="header contact_header">{$_TR.email}</td>
			<td class="header contact_header">{$_TR.contact_type}</td>
			<td class="header contact_header"></td>
		</tr>
		{foreach from=$contacts item=c}
			<tr>
				<td>{$c->name}</td>
				<td>{$c->email}</td>
				<td>
					{if $c->user_id != 0}
						{$_TR.user_account}
					{else}
						{$_TR.manual_address}
					{/if}
				</td>
				<td>
					<a class="mngmntlink contact_mngmntlink" href="{link action=edit_contact id=$c->id}">
						<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
					</a>
					<a class="mngmntlink contact_mngmntlink" href="{link action=delete_contact id=$c->id}" onclick="return confirm('{$_TR.delete_confirm}');">
						<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
					</a>
				</td>
			</tr>
		{foreachelse}
			<tr>
				<td><i>{$_TR.no_contacts}</i></td>
			</tr>
		{/foreach}
	</table>
</div>