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
 * $Id: Default.tpl,v 1.7 2005/04/08 15:45:48 filetreefrog Exp $
 *}
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}" title="Assign permissions on this Module"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}" title="Assign group permissions on this Module"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" /></a>
{/if}
{/permissions}
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.configure == 1}
	<a href="{link action=configure _common=1}" title="Configure this Module"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}configure.png" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}

<div class="bb_boards">
<div class="moduletitle bb_moduletitle">{$moduletitle}</div>
<table cellspacing="1" cellpadding="0" border="0" width="100%">
<tr class="bb_boardlist_header" >
	<td colspan="2">FORUM</td>
	<td>TOPICS</td>
	<!-- td class="bb_boardlist_header">POSTS</td -->
	<td>LAST POST</td>
</tr>
{foreach from=$boards item=board}
<tr class="bb_row">
	<td colspan="2">
		<b><a class="mngmntlink bb_mngmntlink" href="{link module="bbmodule" action="view_board" id=$board->id}">{$board->name}</a></b>
		<br />		<span class="bb_boarddesc">{$board->description}</span> {permissions level=$smarty.const.UILEVEL_NORMAL}
        	<div class="bb_editcontrolls">{if $permissions.edit_board == 1 || $board->permissions.edit_board == 1} <a style="border: 0px" href="{link action=edit_board id=$board->id}" title="Edit the name or description for this board">
                	<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" border="0"/>
        	</a> {/if}
        	{if $permissions.delete_board == 1 || $board->permissions.delete_board == 1} <a style="border: 0px" href="{link action=delete_board id=$board->id}" title="Delete this board">
                	<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" border="0"/>
        	</a>
        	{/if}
        	{/permissions}</div>	</td>
	<td align="center" class=""> 
		{$board->num_topics}	</td>
	<!-- td align="center" class="">stuff</td-->
	<td align="center" class="">
		{if $board->last_post == null}
			No Posts
		{else}
			<span class="bb_date">{$board->last_post->posted|format_date:"%D %T"}</span> <br /> 
			{attribution user=$board->last_post->poster} 
			<a style="border: 0px solid black" href="{link action=view_thread id=$board->last_post->id}" title="View latest post"><img src="{$smarty.const.ICON_RELATIVE}expmode.png" border="0"></a>
	  {/if}	</td>
</tr>
{foreachelse}
<tr>
	<td><i>No bulletin boards were found</i></td>
</tr>
{/foreach}
</table>
</div>
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.create_board == 1}
<a class="mngmntlink bb_mngmntlink" href="{link action=edit_board}">New Board</a>
{/if}
{/permissions}

{if $show_users == true}
<div class="moduletitle bb_moduletitle" style="margin-top: 45px;">Who's Online</div>
<table cellspacing="1" cellpadding="5" border="0" width="100%">
<tr class="bb_boardlist_header">
  <td>{$total_users} visitors in the last 15 minutes: {$num_members} Members - {$anon_users} Guests</td>
</tr>
<tr>
  <td>
    <!--span style="font-size:6px;-->
	<img class="mngmnt_icon" border="0" align="absmiddle" src="{$smarty.const.ICON_RELATIVE}icon_world2.gif" />
    {foreach from=$users_online item=user}
      <a href="{link module=loginmodule action=showuserprofile id=$user->id}" title="View user profile">{$user->username}</a>&nbsp;
    {/foreach}
    <!--/span-->
  </td>
</tr>
</table>
{/if}
