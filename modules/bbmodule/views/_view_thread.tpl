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
 * $Id: _view_thread.tpl,v 1.7 2005/04/08 15:45:49 filetreefrog Exp $
 *}


<div class="bbmodule view-thread">

	<div class="post" >
		<div class="editicons">
			{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
				{if $permissions.administrate == 1}
					{capture assign=int}p{$thread->id}{/capture}
					<a href="{link action=userperms _common=1 int=$int}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>&nbsp;
					<a href="{link action=groupperms _common=1 int=$int}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
				{/if}
			{/permissions}
			{permissions level=$smarty.const.UILEVEL_NORMAL}
				{if $permissions.edit_post == 1 || $thread->poster->id == $currentuser->id}
					<a style="border:none;" class="mngmntlink bb_mngmntlink" href="{link action=edit_post id=$thread->id}">
					<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
					</a>
				{/if}
				{if $permissions.delete_thread == 1}
					<a style="border:none;"  href="{link action=delete_post id=$thread->id}">
					<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
					</a>
				{/if}
			{/permissions}
		</div>
		<a class="backtoboard" href="{link module=bbmodule action=view_board id=$board_id}">Back to "{$board_name}"</a>
		<h1>{$thread->subject}</h1>
		<div class="bio" >
			<h3>{$thread->poster->username}</h3>
			<div class="avatar">
				{if $thread->poster->avatar_path != ""}
					<a href="{link module=loginmodule action=showuserprofile id=$thread->poster->id}" title="View user profile"><img src="{$thread->poster->avatar_path}" /></a>
				{/if}
				{if $thread->poster->bb_user->hide_online_status != 0}<br />
					I'm Online
				{/if}
			</div>
			{foreach from=$thread->poster->ranks item=rank}
				<b>{$rank->title}</b>
			{/foreach}
			<div class="postcount">
				{$thread->poster->bb_user->num_posts} Posts
			</div>
			{if $thread->poster->bb_user->signature}
			<div class="signature">{$thread->poster->bb_user->signature}</div>
			{/if}
		</div>
		<div class="author">
			{if $user != ""}
				An anonymous user replies on {$thread->posted|format_date:"%D %T"}:
			{else}
				<a href="{link module=loginmodule action=showuserprofile id=$thread->poster->id}" title="View user profile">{$thread->poster->username}</a> posts on {$thread->posted|format_date:"%D %T"} :
			{/if}
		</div>
		<br />
		<div class="postbody">{$thread->body}</div>
	</div>	
	<div class="quoteandreply">
		{if $permissions.reply == 1}
			<a href="{link action=edit_post parent=$thread->id }"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}btn_postreply.gif" title="{$_TR.alt_postreply}" alt="{$_TR.alt_postreply}" /></a>
			<a href="{link module=bbmodule action=edit_post parent=$thread->id quote=$thread->id}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}btn_qtr.jpg" title="{$_TR.alt_btn_qtr}" alt="{$_TR.alt_btn_qtr}" /></a>
		{else}
			<a href="{link module=loginmodule action=loginredirect redirecturl=$__redirect}">Login to reply to this topic.</a>
		{/if}
	</div>
	
	
	<!-- replies  -->
	<h2 class="replies">Replies:</h2>
	{foreach from=$replies item=reply}
	<div class="reply {cycle values="odd,even"}">
		<div class="editicons">	
			{permissions level=$smarty.const.UILEVEL_NORMAL}
				{if $permissions.edit_post == 1 || $reply->poster->id == $currentuser->id}
					<a href="{link action=edit_post id=$reply->id}">
					<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
					</a>
				{/if}
				{if $permissions.delete_thread == 1 || $reply->poster->id == $currentuser->id}
					<a href="{link action=delete_post id=$reply->id}">
					<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
					</a>
				{/if}
			{/permissions}
		</div>
		<div class="bio" >
			<h3>{$reply->poster->username}</h3>
			<div class="avatar">
				{if $reply->poster->avatar_path != ""}
					<a href="{link module=loginmodule action=showuserprofile id=$reply->poster->id}" title="View user profile" ><img class="mngmnt_icon" style="border:none;" src="{$reply->poster->avatar_path}" /></a>
				{/if}
				{if $reply->poster->bb_user->hide_online_status != 0}<br />
					I'm Online
				{/if}
			</div>
			{foreach from=$reply->poster->ranks item=rank}
				<strong>{$rank->title}</strong><br />
			{/foreach}
			<div class="postcount">
				{$reply->poster->bb_user->num_posts} Posts
			</div>
			{if $reply->poster->bb_user->signature}
				<div class="signature">{$reply->poster->bb_user->signature}</div>
			{/if}
		</div>
		{if $reply->subject}<h2>{$reply->subject}</h2>{else}<h2>re: {$thread->subject}</h2>{/if}
		<div class="author">
			{if $user != ""}
				An anonymous user replies:
			{else}
				<a href="{link module=loginmodule action=showuserprofile id=$reply->poster->id}" title="View user profile">{$reply->poster->username}</a> replies on {$reply->posted|format_date:"%D %T"} :
			{/if}
		</div>
		<div class="postbody">
			{if $reply->quote}
			<div class="quote">
				<span>quote:</span><br />
				{$reply->quote}
			</div>
			{/if}
			{$reply->body}
		</div><br />
		<div class="quoteandreply">
		{if $permissions.reply == 1}
			<a href="{link module=bbmodule action=edit_post parent=$thread->id quote=$reply->id}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}btn_qtr.jpg" title="{$_TR.alt_btn_qtr}" alt="{$_TR.alt_btn_qtr}" /></a>
		{/if}
		</div>		
	</div>
{/foreach}

{if $permissions.reply == 1}
<br /><a href="{link action=edit_post parent=$thread->id}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}btn_postreply.gif"  title="{$_TR.alt_postreply}" alt="{$_TR.alt_postreply}" /></a>
{else}
  {if $loggedin == 1}
    You are not allowed to post to this topic
  {else}
    <br /><a href="{link module=loginmodule action=loginredirect redirecturl=$__redirect}">Login to reply to this topic.</a>
  {/if}
{/if}<br /><br />
{if $loggedin == 1}
{if $monitoring == 1}
You are monitoring this thread for new replies.
<br /><a class="mngmntlink bb_mngmntlink" href="{link action=monitor_thread id=$thread->id monitor=0}">Click here</a> to stop monitoring it.
{else}
You are not monitoring this thread.
<br /><a class="mngmntlink bb_mngmntlink" href="{link action=monitor_thread id=$thread->id monitor=1}">Click here</a> to start monitoring it for new replies.
{/if}
{/if}
</div>

