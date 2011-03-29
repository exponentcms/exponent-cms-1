{*
 * Copyright (c) 2004-2011 OIC Group, Inc.
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

<table cellspacing="0" cellpadding="2" border="0" width="100%">
	<tr>
		<td class="header banner_header">{$_TR.affiliate}</td>
		<td class="header banner_header">{$_TR.banners}</td>
		<td class="header banner_header">{$_TR.contact_info}</td>
		<td class="header banner_header"></td>
	</tr>
	{foreach from=$affiliates item=a}
		<tr>
			<td valign="top">{$a->name}</td>
			<td valign="top">{$a->bannerCount}</td>
			<td valign="top">{$a->contact_info}</td>
			<td valign="top">
				{permissions level=$smarty.const.UILEVEL_NORMAL}
					{if $permissions.manage_af == 1}
					<a class="mngmntlink banner_mngmntlink" href="{link action=af_edit id=$a->id}">
						<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
					</a>
					<a class="mngmntlink banner_mngmntlink" href="{link action=af_delete id=$a->id}" onclick="return confirm('{$_TR.delete_confirm}');">
						<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
					</a>
					{/if}
				{/permissions}
			</td>
		</tr>
	{foreachelse}
		<tr>
			<td colspan="3" align="center">
				<i>{$_TR.no_affiliates}</i>
			</td>
		</tr>
	{/foreach}
</table>
{permissions level=$smarty.const.UILEVEL_NORMAL}
	{if $permissions.manage_af == 1}
		<a class="mngmntlink banner_mngmntlink" href="{link action=af_edit}">{$_TR.new_affiliate}</a>
	{/if}
{/permissions}