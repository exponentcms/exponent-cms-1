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

	{if $moduletitle != ""}<h1>{$moduletitle}</h1>{/if}
	<table cellspacing="0" cellpadding="0" border="1">
	<tr>
		<th>Forum</td>
		<th>Topics</td>
		<th>Last Post</td>
	</tr>
	{foreach from=$boards item=board}
	<tr class="bbrow {cycle values='odd,even'}">
		<td>
			<b><a class="mngmntlink bb_mngmntlink" href="{link module="bbmodule" action="view_board" id=$board->id}">{$board->name}</a></b>
			<br />		<span class="bb_boarddesc">{$board->description}</span> {permissions level=$smarty.const.UILEVEL_NORMAL}
				<div class="bb_editcontrolls">{if $permissions.edit_board == 1 || $board->permissions.edit_board == 1} <a style="border: 0px" href="{link action=edit_board id=$board->id}" title="Edit the name or description for this board">
						<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
				</a> {/if}
				{if $permissions.delete_board == 1 || $board->permissions.delete_board == 1} <a style="border: 0px" href="{link action=delete_board id=$board->id}" title="Delete this board">
						<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
				</a>
				{/if}
				{/permissions}</div>	
			</td>
			<td align="center" class="">
			{$board->num_topics}</td>
		<td align="center" class="">
			{if $board->last_post == null}
				No Posts
			{else}
				<span class="bb_date">{$board->last_post->posted|format_date:"%D %T"}</span> <br /> 
				<a href="{link action=showuserprofile module=loginmodule id=$board->last_post->poster->id}">{attribution user=$board->last_post->poster}</a>
				<a href="{link action=view_thread id=$board->last_post->id}" title="View latest post"><img src="{$smarty.const.ICON_RELATIVE}expmode.png" title="{$_TR.alt_expmode}" alt="{$_TR.alt_expmode}" /></a>
			{/if} 
		</td>
	</tr>
	{foreachelse}
	<tr>
		<td><i>No bulletin boards were found</i></td>
	</tr>
	{/foreach}
	</table>
	{permissions level=$smarty.const.UILEVEL_NORMAL}
	<div class="moduleactions">
	{if $permissions.create_board == 1}
		<a class="mngmntlink bb_mngmntlink" href="{link action=edit_board}">New Board</a><br />
	{/if}
	{if $loggedin == 1}
	{if $monitoring == 1}
		<br /><br /><i>You are monitoring one or more boards from this forum for new threads.</i>
		<br /><i><a class="mngmntlink bb_mngmntlink" href="{link action=monitor_all_boards monitor=0}">Click here</a> to stop monitoring it.</i>
	{else}
		You are not monitoring this board.
		<br /><a class="mngmntlink bb_mngmntlink" href="{link action=monitor_all_boards monitor=1}">Click here</a> to start monitoring it for new threads.</i>
	{/if}
	{/if}
	</div>
	{/permissions}
	
	{if $show_users == true}
	<div class="moduletitle bb_moduletitle" style="margin-top: 45px;">Who's Online</div>
		<table cellspacing="1" cellpadding="5" style="border:none;" width="100%">
			<tr class="bb_boardlist_header">
				<td>{$total_users} visitors in the last 15 minutes: {$num_members} Members - {$anon_users} Guests</td>
			</tr>
			<tr>
				<td>
					<img class="mngmnt_icon" style="border:none; " src="{$smarty.const.ICON_RELATIVE}icon_world2.gif" title="{$_TR.alt_world2}" alt="{$_TR.alt_world2}" />
					{foreach from=$users_online item=user}
						<a href="{link module=loginmodule action=showuserprofile id=$user->id}" title="View user profile">{$user->username}</a>&nbsp;
					{/foreach}
				</td>
			</tr>
		</table>
	</div>
	{/if}
</div>


