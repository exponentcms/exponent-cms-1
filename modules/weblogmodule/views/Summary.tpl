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
<h2>
{if $enable_rss == true}
	<a href="{rsslink}"><img src="{$smarty.const.ICON_RELATIVE}rss-feed.gif" title="{$_TR.alt_rssfeed}" alt="{$_TR.alt_rssfeed}" /></a>
{/if}
{if $moduletitle != ""}{$moduletitle}{/if}
</h2>
<div>
{foreach from=$posts item=post}
	<b>{$post->posted|format_date:$smarty.const.DISPLAY_DATE_FORMAT}</b>&nbsp;
	{br}- <a href="{link module=weblogmodule action=view id=$post->id}" title="{$post->body|summarize:html:para}">{$post->title}</a>{if $post->is_draft} <span class="draft"><em>({$_TR.draft})</em></span>{/if}
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
	{/permissions}
	{br}
{/foreach}
<hr />
</div>
{if $total_posts > $num_posts}
	<p><a class="mngmntlink weblog_mngmntlink" href="{link action=view_page page=0 view=2}">{$_TR.next} &gt;</a></p>
{/if}
{permissions level=$smarty.const.UILEVEL_NORMAL}
	{if $permissions.post == 1}
		<p><a class="addpost" href="{link action=post_edit}">{$_TR.new_post}</a></p>
	{/if}
{/permissions}
{if $logged_in == 1 || $monitoring == 1}
	<div class="moduleactions">
		{if $logged_in == 1}
			{if $monitoring == 1}
				<em>{$_TR.blog_monitor}</em>
				{br}<a class="mngmntlink bb_mngmntlink" href="{link action=monitor_blog monitor=0}">{$_TR.click_here}</a>{$_TR.stop_monitoring}
			{else}
				<em>{$_TR.not_monitoring}</em>
				{br}<a class="mngmntlink bb_mngmntlink" href="{link action=monitor_blog monitor=1}">{$_TR.click_here}</a>{$_TR.start_monitor}
			{/if}
		{/if}
	</div>
{/if}
