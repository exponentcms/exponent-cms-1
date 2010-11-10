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

<div class="weblogmodule by-tag">
<h2>
{if $enable_rss == true}
	<a href="{rsslink}"><img src="{$smarty.const.ICON_RELATIVE}rss-feed.gif" title="{$_TR.alt_rssfeed}" alt="{$_TR.alt_rssfeed}" /></a>
{/if}
{if $moduletitle != ""}{$moduletitle}{/if}
</h2>
<ul>
{foreach from=$tags item=tag}
	{if $tag->cnt != ""}
		<li><a href="{link action=view_bytag id=$tag->id}">{$tag->name} ({$tag->cnt})</a></li>
	{else}
		<li><i>No Posts Tagged with "{$tag->name}"</i></li>
	{/if}
{/foreach}
</ul>
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.post == 1}
<a class="addpost" href="{link action=post_edit}">{$_TR.create}</a>
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
