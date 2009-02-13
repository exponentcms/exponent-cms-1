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
 * PURPOSE.	 See the GNU General Public License
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
 * $Id: Default.tpl,v 1.7 2005/04/08 15:45:48 filetreefrog Exp $
 *}
<div class="bbmodule default">

	{include file="`$smarty.const.BASE`modules/common/views/_permission_icons.tpl"}

	<table>
		{if $moduletitle != ""}<caption>{$moduletitle}</caption>{/if}
		<tr>
			<th>{$_TR.title}</th>
			<th>{$_TR.topics}</th>
			<th>{$_TR.lastpost}</th>
		</tr>
		{foreach from=$boards item=board}
			<tr class="bbrow {cycle values='odd,even'}">
				<td>
					<strong><a class="mngmntlink bb_mngmntlink" href="{link module="bbmodule" action="view_board" id=$board->id}">{$board->name}</a></strong>
					<div class="bb_boarddesc">{$board->description}</div>
					{permissions level=$smarty.const.UILEVEL_NORMAL}
						{if $permissions.edit_board == 1 || $board->permissions.edit_board == 1 || $permissions.delete_board == 1 || $board->permissions.delete_board == 1} 
							<div class="bb_editcontrols">
								{if $permissions.edit_board == 1 || $board->permissions.edit_board == 1}
									<a href="{link action=edit_board id=$board->id}" title="{$_TR.edit_board}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>
								{/if}
								{if $permissions.delete_board == 1 || $board->permissions.delete_board == 1}
									<a href="{link action=delete_board id=$board->id}" title="{$_TR.del_board}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" /></a>
								{/if}
							</div>
						{/if}
					{/permissions}	
				</td>
				<td>{$board->num_topics}</td>
				<td>
					{if $board->last_post == null}
						{$_TR.no_posts}
					{else}
						<span class="bb_date">{$board->last_post->posted|format_date:$smarty.const.DISPLAY_DATE_FORMAT}</span>
						<br /> 
						<a href="{link action=showuserprofile module=loginmodule id=$board->last_post->poster->id}">{attribution user=$board->last_post->poster}</a>
						<a href="{link action=view_thread id=$board->last_post->id}" title="View latest post"><img src="{$smarty.const.ICON_RELATIVE}expmode.png" title="{$_TR.alt_expmode}" alt="{$_TR.alt_expmode}" /></a>
					{/if} 
				</td>
			</tr>
		{foreachelse}
			<tr>
				<td><em>{$_TR.no_board}</em></td>
			</tr>
		{/foreach}
	</table>
	{permissions level=$smarty.const.UILEVEL_NORMAL}
		{if $permissions.create_board == 1 || $loggedin == 1 || $monitoring == 1}
			<div class="moduleactions">
				{if $permissions.create_board == 1}
					<a class="mngmntlink bb_mngmntlink" href="{link action=edit_board}">{$_TR.new_board}</a>
					<br />
				{/if}
				{if $loggedin == 1}
					{if $monitoring == 1}
						<br /><br /><em>{$_TR.board_monitor}</em>
						<br /><a class="mngmntlink bb_mngmntlink" href="{link action=monitor_all_boards monitor=0}">{$_TR.stop_monitoring}</a>
					{else}
						{$_TR.not_monitoring}
						<br /><a class="mngmntlink bb_mngmntlink" href="{link action=monitor_all_boards monitor=1}">{$_TR.start_monitor}</a>
					{/if}
				{/if}
			</div>
		{/if}
	{/permissions}

	{if $show_users == true}
		<div class="moduletitle bb_moduletitle whos_online">{$_TR.who_online}</div>
		<table>
			<tr class="bb_boardlist_header">
				<td>{$total_users} {$_TR.totalusers} {$num_members} {$_TR.members} {$anon_users} {$_TR.guests}</td>
			</tr>
			<tr>
				<td>
					<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}icon_world2.gif" title="{$_TR.alt_world2}" alt="{$_TR.alt_world2}" />
					{foreach from=$users_online item=user}
						<a href="{link module=loginmodule action=showuserprofile id=$user->id}" title="{$_TR.view_profile}">{$user->username}</a>&nbsp;
					{/foreach}
				</td>
			</tr>
		</table>
	{/if}
</div>