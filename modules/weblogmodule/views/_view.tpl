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

<div class="weblogmodule view">
	{printer_friendly_link class="printer-friendly-link" text=$_TR.printer_friendly}
	{br}
	<h2>
		{if $moduletitle != ""}{$moduletitle}{/if}
	</h2>
	{if $this_post->title}<h3>{$this_post->title}{if $this_post->is_draft} <span class="draft"><em>{$_TR.draft}</em></span>{/if}</h3>{/if}
	<div class="itemactions">
		{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
			{if $permissions.administrate == 1 || $this_post->permissions.administrate == 1}
				<a href="{link action=userperms _common=1 int=$this_post->id}">
					<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm_one}" alt="{$_TR.alt_userperm_one}" />
				</a>
				<a href="{link action=groupperms _common=1 int=$this_post->id}">
					<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm_one}" alt="{$_TR.alt_groupperm_one}" />
				</a>
			{/if}
		{/permissions}
		{permissions level=$smarty.const.UILEVEL_NORMAL}
			{if $permissions.edit == 1 || $this_post->permissions.edit == 1}
				<a class="mngmntlink weblog_mngmntlink" href="{link action=post_edit id=$this_post->id}">
					<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
				</a>
			{/if}
			{if $permissions.delete == 1 || $this_post->permissions.delete == 1}
				<a class="mngmntlink weblog_mngmntlink" href="{link action=post_delete id=$this_post->id}" onclick="return confirm('{$_TR.delete_confirm}');">
					<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
				</a>
			{/if}
			{if $permissions.manage_approval == 1 || $post->permissions.manage_approval == true}
				<a class="mngmntlink weblog_mngmntlink" href="{link module=workflow datatype=weblog_post m=weblogmodule s=$__loc->src action=revisions_view id=$post->id}" title="{$_TR.alt_revisions}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}revisions.png" title="{$_TR.alt_revisions}" alt="{$_TR.alt_revisions}"/></a>
			{/if}
		{/permissions}
	</div>
	<div class="post-footer">
		{if $this_post->posted > $smarty.now}
			<b><u>{$_TR.will_be}&nbsp;
		{elseif ($this_post->unpublish != 0) && $this_post->unpublish <= $smarty.now}
			<b><u>{$_TR.was}&nbsp;
		{/if}
		{$_TR.posted}{if $config->show_poster} {$_TR.by} {attribution user_id=$this_post->poster} {$_TR.on} {/if}&nbsp;{$this_post->posted|format_date:$smarty.const.DISPLAY_DATE_FORMAT} | Read {$this_post->reads} times
		{if $config->allow_comments != 0}
			| <a class="comments" href="#comments">{$this_post->total_comments} {$_TR.comment}{if $this_post->total_comments != 1}{$_TR.plural}{/if}</a>
		{elseif $config->allow_replys != 0}
			| <a class="comments itemactions" href="#reply">{$_TR.reply}</a>
		{/if}
		{if $this_post->posted > $smarty.now}
			</u></b>&nbsp;
		{elseif ($this_post->unpublish != 0) && $this_post->unpublish <= $smarty.now}
			{$_TR.now_unpublished}</u></b>&nbsp;
		{/if}
	</div>
	<div class="bodycopyfull">
		{if $this_post->image!=""}<img class="weblogimg" src="{$smarty.const.URL_FULL}/thumb.php?file={$this_post->image}&amp;constraint=1&amp;width=150&amp;height=200" alt="{$this_post->title}" />{/if}
		{$this_post->body}
	</div>
	{if $config->allow_comments}
		<a name="comments"></a>
		<div class="post-footer"">
			{if $this_post->is_draft}
				<em>{$_TR.draft_desc}</em>
			{else}
				{br}
				<div class="weblog_itemtitle"><a class="comments"></a><b>{$this_post->total_comments} {$_TR.comment}{if $this_post->total_comments != 1}{$_TR.plural}{/if} - "{$this_post->title}"</b></div>
				{foreach from=$this_post->comments item=comment}
					<div class="weblog_comment_{cycle values="odd,even"}">
						<div class="itemactions">
						{permissions level=$smarty.const.UILEVEL_NORMAL}
							{if $config->approve_comments && ($permissions.approve_comments == 1 || $this_post->permissions.approve_comments == 1)}
								<a class="mngmntlink weblog_mngmntlink" href="{link action=comment_approve id=$comment->id parent_id=$this_post->id}">
								{if $comment->approved}
									<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}thumb_up.png" title="{$_TR.alt_disapprove_comment}" alt="{$_TR.alt_disapprove_comment}" />
								{else}
									<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}thumb_down.png" title="{$_TR.alt_approve_comment}" alt="{$_TR.alt_approve_comment}" />
								{/if}
								</a>
							{/if}
							{if $permissions.edit_comments == 1 || $this_post->permissions.edit_comments == 1}
								<a class="mngmntlink weblog_mngmntlink" href="{link action=comment_edit id=$comment->id parent_id=$this_post->id}">
								<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit_comment}" alt="{$_TR.alt_edit_comment}" />
								</a>
							{/if}
							{if $permissions.delete_comments == 1 || $this_post->permissions.delete_comments == 1}
								<a class="mngmntlink weblog_mngmntlink" href="{link action=comment_delete id=$comment->id parent_id=$this_post->id}" onclick="return confirm('{$_TR.delete_comment_confirm}');">
									<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete_comment}" alt="{$_TR.alt_delete_comment}" />
								</a>
							{/if}
						{/permissions}
						</div>
						<div class="weblog_comment_body">{$comment->body}</div>
						<div class="weblog_comment_attribution">
							{* Removed this to allow different styling for attribution *}
							{* <img src="{$smarty.const.ICON_RELATIVE}arrow_right.gif" title="{$_TR.alt_arrow_right}" alt="{$_TR.alt_arrow_right}" /> *}
							- {if $config->show_poster}{$_TR.comment} {$_TR.by} {$comment->name} -{/if} {$comment->posted|format_date:$smarty.const.DISPLAY_DATE_FORMAT}
						</div>
					</div>
				{/foreach}
			{/if}	
		</div>	
		{/if}
	{if $tagcnt > 0}
		<div style="border: 1px solid gray; padding: 10px;;">
			<b>Tags:</b> 
			{foreach from=$tags item=tag}
				{$tag->name};
			{/foreach}
		</div>
		{br}
	{/if}
	<div class="itemactions">
		{if (($config->allow_comments != 0) && ($config->allow_replys != 0))}
			<p>Do you want to <a id="myHeader1" href="javascript:showonlyone('reply1');" title="Click to select Public Comment">{$_TR.leave_comment}</a> or 
			<a id="myHeader2" href="javascript:showonlyone('reply2');" title="Click to select Private Reply">{$_TR.send_response}</a></p>
		{/if}
		{if $config->allow_comments != 0}
			<div name="replies" id="reply1" style="display: block">
				<a name="reply"></a>
				{if $config->require_login == 0 || ($config->require_login == 1 && $logged_in == 1)}
					<h4>{$_TR.leave_comment} - from {$user->email} &lt;{$user->username}&gt;</h4>
					{form name=weblogcomment action=comment_save}
						{control type=hidden name=parent_id value=$this_post->id}
						{if $logged_in == 1}
							{control type=hidden name=name value=$user->username}
							{control type=hidden name=email value=$user->email}
						{else}
							{control type=text name=name label=Name}
							{control type=text name=email label=Email}
						{/if}
						{control type=textarea name=body label=Comment}
						{if $config->use_captcha == 1}{control type=captcha label="Security Verification"}{/if}
						{if $config->approve_comments == 1}
							<div><b>ALL comments must be moderated before being listed!</b></div>
						{/if}
						{control type=buttongroup submit=Save}
					{/form}
				{else}
					<p>{$_TR.need_login}{if $smarty.const.SITE_ALLOW_REGISTRATION == 1} <a href="{link module=loginmodule action=loginredirect}">{$_TR.login}</a>{/if}</p>
				{/if}
			</div>
		{/if}
		{if $config->allow_replys != 0}
			<div name="replies" id="reply2" style="display: {if $config->allow_comments != 0}none{else}block{/if}">
				<a name="reply"></a>
				{if $config->require_login == 0 || ($config->require_login == 1 && $logged_in == 1)}
					<h4>{$_TR.send_response} - from {$user->email} &lt;{$user->username}&gt;</h4>
					{form name=weblogreply action=reply_send}
						{control type=hidden name=msg value="_Default"}
						{control type=hidden name=parent_id value=$this_post->id}
						{control type=hidden name=subject value=$subject}
						{control type=hidden name=emailto value=$emailto}
						{if $logged_in == 1}
							{control type=hidden name=name value=$user->username}
							{control type=hidden name=email value=$user->email}
						{else}
							{control type=text name=name label=Name}
							{control type=text name=email label=Email}
						{/if}
						{control type=textarea name=message label=Comment}
						{if $config->use_captcha == 1}{control type=captcha label="Security Verification"}{/if}
						{control type=buttongroup submit=Send}
					{/form}
				{else}
					<p>{$_TR.need_login_reply}{if $smarty.const.SITE_ALLOW_REGISTRATION == 1} <a href="{link module=loginmodule action=loginredirect}">{$_TR.login_reply}</a>{/if}</p>
				{/if}
			</div>
		{/if}
		{if $logged_in == 1 || $monitoring == 1}
			<div class="moduleactions">
				{if $logged_in == 1}
					{if $monitoring == 1}
						<em>{$_TR.blog_monitor}</em>
						{br}<a class="bb_mngmntlink" href="{link action=monitor_blog monitor=0}">{$_TR.click_here}</a>{$_TR.stop_monitoring}
					{else}
						<em>{$_TR.not_monitoring}</em>
						{br}<a class="bb_mngmntlink" href="{link action=monitor_blog monitor=1}">{$_TR.click_here}</a>{$_TR.start_monitor}
					{/if}
				{/if}
			</div>
		{/if}		
		{if $prev_post != 0 || $next_post != 0}
			<div class="paging">
				<hr>
				<span class="previous">
					{if $prev_post != 0} 
						<a class="weblog_mngmntlink" href="{link action=view id=$prev_post->id}">{$prev_post->title}</a>&nbsp;{$_TR.previous}
					{else}
						&nbsp;
					{/if}
				</span>
				<span class="next">
					{if $next_post!=0}
						{$_TR.next}&nbsp;<a class="weblog_mngmntlink" href="{link action=view id=$next_post->id}">{$next_post->title}</a>
					{else}
						&nbsp;
					{/if}
				</span>
			</div>
		{/if}
	</div>
</div>

<script language="javascript">
{literal}
function showonlyone(thechosenone) {
	var replies = document.getElementsByTagName("div");
	for(var x=0; x<replies.length; x++) {
		name = replies[x].getAttribute("name");
		if (name == 'replies') {
			if (replies[x].id == thechosenone) {
				replies[x].style.display = 'block';
			} else {
				replies[x].style.display = 'none';
			}
		}
	}
} 
{/literal}
</script>
