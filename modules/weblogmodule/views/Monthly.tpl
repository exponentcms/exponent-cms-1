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
 
<div class="weblogmodule monthly">
	<h2>
	{if $enable_rss == true}
		<a href="{rsslink}"><img src="{$smarty.const.ICON_RELATIVE}rss-feed.gif" title="{$_TR.alt_rssfeed}" alt="{$_TR.alt_rssfeed}" /></a>
	{/if}
	{if $moduletitle != ""}{$moduletitle}{/if}
	</h2>
	<div>
	<ul>
	{foreach from=$months key=m_ts item=count}
		<li><a class="mngmntlink weblog_mngmntlink weblog_monthview_mngmntlink" href="{link action=view_month month=$m_ts}">{$m_ts|format_date:"%B %Y"} ({$count})</a></li>
	{/foreach}
	</ul>
	</div>
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
