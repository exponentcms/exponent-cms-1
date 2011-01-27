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
 
<div class="weblogmodule view-month">
	<h2>
	{if $enable_rss == true}
		<a href="{rsslink}"><img src="{$smarty.const.ICON_RELATIVE}rss-feed.gif" title="{$_TR.alt_rssfeed}" alt="{$_TR.alt_rssfeed}" /></a>
	{/if}
	{if $moduletitle != ""}{$moduletitle}{/if} - {$now|format_date:"%B %Y"}
	</h2>
	{foreach from=$posts item=post}
	<div class="item">
	<h3><a href="{link module=weblogmodule action=view id=$post->id}">{$post->title}</a>{if $post->is_draft} <span class="draft"><em>({$_TR.draft})</em></span>{/if}</h3>
	<div class="itemactions">
	{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
	{if $permissions.administrate == 1 || $post->permissions.administrate == 1}
	<a href="{link action=userperms _common=1 int=$post->id}">
		<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm_one}" alt="{$_TR.alt_userperm_one}" />
	</a>
	<a href="{link action=groupperms _common=1 int=$post->id}">
		<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm_one}" alt="{$_TR.alt_groupperm_one}" />
	</a>
	{/if}
	{/permissions}
	{permissions level=$smarty.const.UILEVEL_NORMAL}
	{if $permissions.edit == 1 || $post->permissions.edit == 1}
	<a class="mngmntlink weblog_mngmntlink" href="{link action=post_edit id=$post->id}">
		<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
	</a>
	{/if}
	{if $permissions.delete == 1 || $post->permissions.delete == 1}
	<a class="mngmntlink weblog_mngmntlink" href="{link action=post_delete id=$post->id}" onclick="return confirm('{$_TR.delete_confirm}');">
		<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
	</a>
	{/if}
	{if $permissions.manage_approval == 1 || $post->permissions.manage_approval == true}
		<a class="mngmntlink weblog_mngmntlink" href="{link module=workflow datatype=weblog_post m=weblogmodule s=$__loc->src action=revisions_view id=$post->id}" title="{$_TR.alt_revisions}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}revisions.png" title="{$_TR.alt_revisions}" alt="{$_TR.alt_revisions}"/></a>
	{/if}
	{/permissions}
	</div>
	<div class="bodycopyfull">
		{$post->body}
	</div>
	<div class="post-footer">
		<a class="readmore" href="{link module=weblogmodule action=view id=$post->id}">{$_TR.read_more}<span> "{$post->title}"</span></a>
		| Read {$post->reads} times
		{if $config->allow_comments != 0}
			| <a class="comments" href="{link module=weblogmodule action=view id=$post->id}#comments">{$post->total_comments} {$_TR.comment}{if $post->total_comments != 1}{$_TR.plural}{/if}<span> {$_TR.on} "{$post->title}"</span></a>
		{elseif $config->allow_replys != 0}
			| <a class="replys" href="{link module=weblogmodule action=view id=$post->id}#reply">{$_TR.reply}<span> {$_TR.to} "{$post->title}"</span></a>
		{/if}
		 |
		{if $post->posted > $smarty.now}
			<b><u>{$_TR.will_be}&nbsp;
		{elseif ($post->unpublish != 0) && $post->unpublish <= $smarty.now}
			<b><u>{$_TR.was}&nbsp;
		{/if}
		{$_TR.posted}{if $config->show_poster} {$_TR.by} {attribution user_id=$post->poster} {$_TR.on} {/if}&nbsp;{$post->posted|format_date:$smarty.const.DISPLAY_DATE_FORMAT}
		{if $post->posted > $smarty.now}
			</u></b>&nbsp;
		{elseif ($post->unpublish != 0) && $post->unpublish <= $smarty.now}
			{$_TR.now_unpublished}</u></b>&nbsp;
		{/if}
	</div>
	<hr />
	</div>
	{/foreach}
	{permissions level=$smarty.const.UILEVEL_NORMAL}
	{if $permissions.post == 1}
	<a class="addpost" href="{link action=post_edit}">{$_TR.new_post}</a>
	{/if}
	{/permissions}
	{if $logged_in == 1 || $monitoring == 1}
		<div class="moduleactions">
			{if $logged_in == 1}
				{if $monitoring == 1}
					{br}<em>{$_TR.blog_monitor}</em>
					{br}<a class="mngmntlink bb_mngmntlink" href="{link action=monitor_blog monitor=0}">{$_TR.click_here}</a>{$_TR.stop_monitoring}
				{else}
					{br}<em>{$_TR.not_monitoring}</em>
					{br}<a class="mngmntlink bb_mngmntlink" href="{link action=monitor_blog monitor=1}">{$_TR.click_here}</a>{$_TR.start_monitor}
				{/if}
			{/if}
		</div>
	{/if}
</div>
