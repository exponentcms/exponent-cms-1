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
<div class="weblogmodule default">
<h1>
{if $enable_rss == true}
        <a class="rsslink" href="{rsslink}"><img src="{$smarty.const.ICON_RELATIVE}rss-feed.gif" title="{$_TR.alt_rssfeed}" alt="{$_TR.alt_rssfeed}" /></a>
{/if}
{if $moduletitle != ""}{$moduletitle}{/if}
</h1>
{include file="`$smarty.const.BASE`modules/common/views/_permission_icons.tpl"}
{foreach from=$posts item=post}
	<div class="item {cycle values='odd,even'}">
		<div>
			<h2 class="itemtitle weblog_itemtitle">{$post->title}{if $post->is_draft} <span class="draft">(Draft)</span>{/if}</h2>
			<div class="attribution">Posted by {attribution user_id=$post->poster} on {$post->posted|format_date:$smarty.const.DISPLAY_DATE_FORMAT}</div>
			<br />
			{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
			{if $permissions.administrate == 1 || $post->permissions.administrate == 1}
			<a href="{link action=userperms _common=1 int=$post->id}">
				<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm_one}" alt="{$_TR.alt_userperm_one}" />
			</a>
			<a href="{link action=groupperms _common=1 int=$post->id}">
				<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm_one}" alt="{$_TR.alt_groupperm_one}" />
			</a>
			{/if}
			{/permissions}
			{permissions level=$smarty.const.UILEVEL_NORMAL}
			{if $permissions.edit == 1 || $post->permissions.edit == 1}
			<a class="mngmntlink weblog_mngmntlink" href="{link action=post_edit id=$post->id}">
			<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
			</a>
			{/if}
			{if $permissions.delete == 1 || $post->permissions.delete == 1}
			<a class="mngmntlink weblog_mngmntlink" href="{link action=post_delete id=$post->id}" onclick="return confirm('{$_TR.delete_confirm}');">
			<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
			</a>
			{/if}
			{/permissions}
		</div>
		<div class="bodycopy">{$post->body}</div>
		{if $config->allow_comments}
			<p class="comments post-footer">
    				<a class="comments" href="{link action=findByTitle title=$post->title}">Comment{if $post->total_comments != 1}s{/if} ({$post->total_comments})</a>
			</p>
		{/if}
		<hr size="1" />
	</div>
{/foreach}
{if $total_posts > $config->items_per_page}
	<a class="mngmntlink weblog_mngmntlink" href="{link action=view_page page=1}">{$_TR.next}</a>
{/if}
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.post == 1}
<br />
<a class="mngmntlink weblog_mngmntlink" href="{link action=post_edit}">{$_TR.new_post}</a>
{/if}
{/permissions}
</div>
