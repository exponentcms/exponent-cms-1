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


<div style="padding:15px;">
	<img class="mngmnt_icon" style="border:none; float:left;" src="{$smarty.const.ICON_RELATIVE}arrow_left.gif" title="{$_TR.previous}" alt="{$_TR.previous}" /><a href="{link module=bbmodule action=view_board id=$board_id}">Back to {$board_name}</a><br />
<br />

	<div class="bb_postdiv"  style="display:table; width:100%; padding:0 0 10px 0">
		<div class="bb_editicons">
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
		<div class="bb_postsubject">{$thread->subject}</div>
		<div class="bb_bio" >
			<div class="bb_avitar" style="text-align:center">
				{if $thread->poster->avatar_path != ""}
					<a href="{link module=loginmodule action=showuserprofile id=$thread->poster->id}" title="View user profile" style="border:none;"><img src="{$thread->poster->avatar_path}" style="border:none;" /></a>
				{/if}
				{if $thread->poster->bb_user->hide_online_status != 0}<br />
					I'm Online
				{/if}
			</div>
			<strong>{$thread->poster->username}</strong><br />
       {foreach from=$thread->poster->ranks item=rank}
        <strong>{$rank->title}</strong><br />
      {/foreach}
			Posted on {$thread->posted|format_date:"%D %T"}<br />
			Number of posts: {$thread->poster->bb_user->num_posts}
			{if $thread->poster->bb_user->signature}
			<div class="bb_signature">{$thread->poster->bb_user->signature}</div>
			{/if}
		</div>
		<div class="bb_author">
			{attribution user=$thread->poster->username} 
			{if $user != ""}
				An anonymous user replies:
			{else}
				<a href="{link module=loginmodule action=showuserprofile id=$thread->poster->id}" title="View user profile">{$thread->poster->username}</a> posts:
			{/if}
		</div>
		<br />
		<div class="bb_postbody">{$thread->body}</div>
	</div>	
	<div>
		{if $permissions.reply == 1}
			<a href="{link action=edit_post parent=$thread->id }" style="border:none;"><img style="border:none;" src="{$smarty.const.ICON_RELATIVE}btn_postreply.gif" title="{$_TR.alt_postreply}" alt="{$_TR.alt_postreply}" /></a>
			<a href="{link module=bbmodule action=edit_post parent=$thread->id quote=$thread->id}" style="border:none;"><img style="border:none;" src="{$smarty.const.ICON_RELATIVE}btn_qtr.jpg" title="{$_TR.alt_btn_qtr}" alt="{$_TR.alt_btn_qtr}" /></a>
		{else}
			<a href="{link module=loginmodule action=loginredirect redirecturl=$__redirect}">Login to reply to this topic.</a>
		{/if}
	</div>
	
	
	<!-- replies  -->
	<h3 class="bbreplies">Replies:</h3>
	{foreach from=$replies item=reply}
	<div class="bb_reply{cycle values="_light,_dark"}" style="display:table;">
		<div class="bb_editicons" style="float:right">	
			{permissions level=$smarty.const.UILEVEL_NORMAL}
				{if $permissions.edit_post == 1 || $reply->poster->id == $currentuser->id}
					<a style="border:none;" href="{link action=edit_post id=$reply->id}">
					<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
					</a>
				{/if}
				{if $permissions.delete_thread == 1 || $reply->poster->id == $currentuser->id}
					<a style="border-bottom:none;" href="{link action=delete_post id=$reply->id}">
					<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
					</a>
				{/if}
			{/permissions}
		</div>
		{if $reply->subject}<div class="bb_replysubject">{$reply->subject}</div>{else}<div class="bb_replysubject">re: {$thread->subject}</div>{/if}
		<div class="bb_bio" >
			<div class="bb_avitar" style="text-align:center">
				{if $reply->poster->avatar_path != ""}
					<a href="{link module=loginmodule action=showuserprofile id=$reply->poster->id}" title="View user profile" style="border:none;"><img src="{$reply->poster->avatar_path}" style="border:none;" /></a>
				{/if}
				{if $reply->poster->bb_user->hide_online_status != 0}<br />
					I'm Online
				{/if}
			</div>
			<strong>{$reply->poster->username}</strong><br />
      {foreach from=$reply->poster->ranks item=rank}
        <strong>{$rank->title}</strong><br />
      {/foreach}
			Posted on {$reply->posted|format_date:"%D %T"}<br />
			Number of posts: {$reply->poster->bb_user->num_posts}
			{if $reply->poster->bb_user->signature}
				<div class="bb_signature">{$reply->poster->bb_user->signature}</div>
			{/if}
		</div>
		<div class="bb_author">
			{attribution user=$reply->poster->username} 
			{if $user != ""}
				An anonymous user replies:
			{else}
				<a href="{link module=loginmodule action=showuserprofile id=$reply->poster->id}" title="View user profile">{$reply->poster->username}</a> replies:
			{/if}
		</div><br />
		<div class="bb_postbody">
			{if $reply->quote}
			<div class="bb_quote">
				<span>quote:</span><br />
				{$reply->quote}
			</div>
			{/if}
			{$reply->body}
		</div><br />
		{if $permissions.reply == 1}
		<div>
			<a href="{link module=bbmodule action=edit_post parent=$thread->id quote=$reply->id}" style="border:none;"><img style="border:none;" src="{$smarty.const.ICON_RELATIVE}btn_qtr.jpg" title="{$_TR.alt_btn_qtr}" alt="{$_TR.alt_btn_qtr}" /></a>
		</div>		
		{/if}
	</div><br />
{/foreach}

{if $permissions.reply == 1}
<br /><a href="{link action=edit_post parent=$thread->id}" style="border:none;"><img style="border:none;" src="{$smarty.const.ICON_RELATIVE}btn_postreply.gif"  title="{$_TR.alt_postreply}" alt="{$_TR.alt_postreply}" /></a>
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

