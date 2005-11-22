{*
 *
 * Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
 * All Changes as of 6/1/05 Copyright 2005 James Hunt
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
	<a href="{link action=userperms _common=1}" title="{$_TR.alt_userperm}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}" title="{$_TR.alt_groupperm}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" /></a>
{/if}
{if $permissions.configure == 1}
	<a href="{link action=configure _common=1}" title="{$_TR.alt_configure}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}configure.png" /></a>
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
				<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" border="0" />
			</a>
			<a class="mngmntlink banner_mngmntlink" href="{link action=af_delete id=$a->id}" onClick="return confirm('{$_TR.delete_confirm}');">
				<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" border="0" />
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