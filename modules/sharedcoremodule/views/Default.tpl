{*
 *
 * Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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
	<a href="{link action=userperms _common=1}" title="Assign permissions on this Module"><img border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}" title="Assign group permissions on this Module"><img border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" /></a>
	<br />
{/if}
{/permissions}
{if $moduletitle != ""}<div class="moduletitle sharedcore_moduletitle">{$moduletitle}</div>{/if}
<table cellpadding="0" cellspacing="0" border="0" width="100%">
{assign var=nocores value=0}
{foreach from=$cores item=core}
	<tr style="background-color: lightgrey;">
		<td>{$core->name} (version {$core->version})</td>
		<td>{$core->path}</td>
		<td>
			{permissions level=$smarty.const.UI_LEVEL_NORMAL}
			{if $permissions.manage == 1}
			<a class="mngmntlink sharedcore_mngmntlink" href="{link action=edit_core id=$core->id}">
				<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}edit.png" />
			</a>
			<a class="mngmntlink sharedcore_mngmntlink" href="{link action=delete_core id=$core->id}">
				<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.png" onClick="return confirm('Are you sure you want to delete this codebase and all sites deployed from it?');" />
			</a>
			{/if}
			{/permissions}
		</td>
	</tr>
	{foreach from=$core->linked item=site}
		<tr>
			<td style="padding-left: 50px">
				{if $site->inactive == 1}
					<i><a target="_blank" class="mngmntlink sharedsite_mngmntlink" href="{$site->relpath}">{$site->name}</a></i>
				{else}
					<a target="_blank" class="mngmntlink sharedsite_mngmntlink" href="{$site->relpath}">{$site->name}</a>
				{/if}
			</td>
			<td>
				{$site->path}
			</td>
			<td>
				{permissions level=$smarty.const.UI_LEVEL_NORMAL}
				{if $permissions.manage == 1}
				<a class="mngmntlink sharedsite_mngmntlink" href="{link action=edit_site id=$site->id}">
					<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}edit.png" />
				</a>
				<a class="mngmntlink sharedsite_mngmntlink" href="{link action=delete_site id=$site->id}" onClick="return confirm('Are you sure you want to delete this deployed site?');">
					<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.png" />
				</a>
				{if $site->inactive == 1}
					<a class="mngmntlink sharedsite_mngmntlink" href="{link action=activate_site id=$site->id}">
						<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}unlock.png" />
					</a>
				{else}
					<a class="mngmntlink sharedsite_mngmntlink" href="{link action=deactivate_form id=$site->id}">
						<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}lock.png" />
					</a>
					<a class="mngmntlink sharedsite_mngmntlink" href="{link action=refresh_site id=$site->id}">
						<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}reload.png" />
					</a>
				{/if}
				{/if}
				{/permissions}
			</td>
		</tr>
	{foreachelse}
		<tr>
			<td colspan="3" style="padding-left: 50px">
				<i>No sites have been deployed from this codebase.</i>
			</td>
		</tr>
	{/foreach}
{foreachelse}
	{assign var=nocores value=1}
	<tr><td align="center"><i>No codebases found</td></tr>
{/foreach}
</table>
{permissions level=$smarty.const.UI_LEVEL_NORMAL}
{if $permissions.manager == 1}
<a class="mngmntlink sharedcore_mngmntlink" href="{link action=edit_core}">New Codebase</a>
{if $nocores == 0}
<br />
<a class="mngmntlink sharedcore_mngmntlink" href="{link action=edit_site core_id=$core->id}">Deploy New Site</a>
{/if}
{/if}
{/permissions}
