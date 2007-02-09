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
	<br />
{/if}
{/permissions}
{if $moduletitle != ""}<div class="moduletitle sharedcore_moduletitle">{$moduletitle}</div>{/if}
<table cellpadding="0" cellspacing="0" style="border:none;" width="100%">
{assign var=nocores value=0}
{foreach from=$cores item=core}
	<tr style="background-color: lightgrey;">
		<td>{$core->name} (version {$core->version})</td>
		<td>{$core->path}</td>
		<td>
			{permissions level=$smarty.const.UILEVEL_NORMAL}
			{if $permissions.manage == 1}
			<a class="mngmntlink sharedcore_mngmntlink" href="{link action=edit_core id=$core->id}">
				<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
			</a>
			<a class="mngmntlink sharedcore_mngmntlink" href="{link action=delete_core id=$core->id}">
				<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.delete}" alt="{$_TR.delete}" onclick="return confirm('{$_TR.delete_core_confirm}');" />
			</a>
			{/if}
			{/permissions}
		</td>
	</tr>
	{foreach from=$core->linked item=site}
		<tr>
			<td style="padding-left: 50px">
				{if $site->inactive == 1}
					<i><a target="_blank" class="mngmntlink sharedsite_mngmntlink" href="{$site->host}{$site->relpath}">{$site->name}</a></i>
				{else}
					<a target="_blank" class="mngmntlink sharedsite_mngmntlink" href="{$site->host}{$site->relpath}">{$site->name}</a>
				{/if}
			</td>
			<td>
				{$site->path}
			</td>
			<td>
				{permissions level=$smarty.const.UILEVEL_NORMAL}
				{if $permissions.manage == 1}
				<a class="mngmntlink sharedsite_mngmntlink" href="{link action=edit_site id=$site->id}">
					<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
				</a>
				<a class="mngmntlink sharedsite_mngmntlink" href="{link action=delete_site id=$site->id}" onclick="return confirm('{$_TR.delete_site_confirm}');">
					<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
				</a>
				{if $site->inactive == 1}
					<a class="mngmntlink sharedsite_mngmntlink" href="{link action=activate_site id=$site->id}">
						<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}unlock.png" title="{$_TR.alt_unlock}" alt="{$_TR.alt_unlock}" />
					</a>
				{else}
					<a class="mngmntlink sharedsite_mngmntlink" href="{link action=deactivate_form id=$site->id}">
						<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}lock.png" title="{$_TR.alt_lock}" alt="{$_TR.alt_lock}" />
					</a>
					<a class="mngmntlink sharedsite_mngmntlink" href="{link action=refresh_site id=$site->id}">
						<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}reload.png" title="{$_TR.alt_reload}" alt="{$_TR.alt_reload}" />
					</a>
				{/if}
				{/if}
				{/permissions}
			</td>
		</tr>
	{foreachelse}
		<tr>
			<td colspan="3" style="padding-left: 50px">
				<i>{$_TR.no_sites}</i>
			</td>
		</tr>
	{/foreach}
{foreachelse}
	{assign var=nocores value=1}
	<tr><td align="center"><i>{$_TR.no_cores}</td></tr>
{/foreach}
</table>
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.manage == 1}
<a class="mngmntlink sharedcore_mngmntlink" href="{link action=edit_core}">{$_TR.new_codebase}</a>
{if $nocores == 0}
<br />
<a class="mngmntlink sharedcore_mngmntlink" href="{link action=edit_site core_id=$core->id}">{$_TR.new_site}</a>
{/if}
{/if}
{/permissions}
