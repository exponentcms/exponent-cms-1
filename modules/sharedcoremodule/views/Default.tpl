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
{if $moduletitle != ""}<div class="moduletitle sharedcore_moduletitle">{$moduletitle}</div>{/if}
<table cellpadding="0" cellspacing="0" border="0" width="100%">
{assign var=nocores value=0}
{foreach from=$cores item=core}
	<tr style="background-color: lightgrey;">
		<td>{$core->name} (version {$core->version})</td>
		<td>{$core->path}</td>
		<td>
			<a class="mngmntlink sharedcore_mngmntlink" href="{$linkbase}edit_core&id={$core->id}">
				<img border="0" src="{$smarty.const.ICON_RELATIVE}edit.gif" />
			</a>
			<a class="mngmntlink sharedcore_mngmntlink" href="{$linkbase}delete_core&id={$core->id}">
				<img border="0" src="{$smarty.const.ICON_RELATIVE}delete.gif" />
			</a>
		</td>
	</tr>
	{foreach from=$core->linked item=site}
		<tr>
			<td style="padding-left: 50px">
				{if $site->inactive == 1}
					<i>{$site->name}</i>
				{else}
					{$site->name}
				{/if}
			</td>
			<td>
				{$site->path}
			</td>
			<td>
				{if $site->inactive == 1}
					<a class="mngmntlink sharedsite_mngmntlink" href="{$linkbase}activate_site&id={$site->id}">
						Activate
					</a>
				{else}
					<a class="mngmntlink sharedsite_mngmntlink" href="{$linkbase}deactivate_form&id={$site->id}">
						Deactivate
					</a>
				{/if}
				<a class="mngmntlink sharedsite_mngmntlink" href="{$linkbase}refresh_site&id={$site->id}">
					Refresh
				</a>
				<a class="mngmntlink sharedsite_mngmntlink" href="{$linkbase}edit_site&id={$site->id}">
					<img border="0" src="{$smarty.const.ICON_RELATIVE}edit.gif" />
				</a>
				<a class="mngmntlink sharedsite_mngmntlink" href="{$linkbase}delete_site&id={$site->id}">
					<img border="0" src="{$smarty.const.ICON_RELATIVE}delete.gif" />
				</a>
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
<a class="mngmntlink sharedcore_mngmntlink" href="{$linkbase}edit_core">New Codebase</a>

{if $nocores == 0}
<br />
<a class="mngmntlink sharedcore_mngmntlink" href="{$linkbase}edit_site&core_id={$core->id}">Deploy New Site</a>
{/if}