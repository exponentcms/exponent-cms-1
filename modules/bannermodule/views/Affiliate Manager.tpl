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
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}" title="{$_TR.alt_userperm}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}" title="{$_TR.alt_groupperm}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
	<a href="{link action=configure _common=1}" title="{$_TR.alt_configure}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}configure.png" title="{$_TR.alt_configure}" alt="{$_TR.alt_configure}" /></a>
{/if}
{if $permissions.configure == 1}
	<a href="{link action=configure _common=1}" title="{$_TR.alt_configure}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}configure.png" title="{$_TR.alt_configure}" alt="{$_TR.alt_configure}" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}
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
