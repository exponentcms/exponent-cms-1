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
 
<div class="weblogmodule mini-cal"> 
	<h2>
	{if $enable_rss == true}
		<a href="{rsslink}"><img src="{$smarty.const.ICON_RELATIVE}rss-feed.gif" title="{$_TR.alt_rssfeed}" alt="{$_TR.alt_rssfeed}" /></a>
	{/if}
	{if $moduletitle != ""}{$moduletitle}{/if}
	</h2>
	<table class="mini-cal">
		<caption><a class="nav doublearrow" href="{link action=view_mini month=$prevmonth view='Mini-Calendar'}" title="{$_TR.alt_previous}">&laquo;</a> {if $posted}<a class="mngmntlink weblog_mngmntlink weblog_monthview_mngmntlink" href="{link action=view_month month=$now}">{/if}{$now|format_date:"%B %Y"}{if $posted}</a>{/if} <a class="nav doublearrow" href="{link action=view_mini month=$nextmonth view='Mini-Calendar'}" title="{$_TR.alt_next}">&raquo;</a></caption>
		<tr class="daysoftheweek">
			{if $smarty.const.DISPLAY_START_OF_WEEK == 0}
				<th scope="col" abbr="{$_TR.sunday}" title="{$_TR.sunday}">{$_TR.sunday_short}</th>
			{/if}
			<th scope="col" abbr="{$_TR.monday}" title="{$_TR.monday}">{$_TR.monday_short}</th>
			<th scope="col" abbr="{$_TR.tuesday}" title="{$_TR.tuesday}">{$_TR.tuesday_short}</th>
			<th scope="col" abbr="{$_TR.wednesday}" title="{$_TR.wednesday}">{$_TR.wednesday_short}</th>
			<th scope="col" abbr="{$_TR.thursday}" title="{$_TR.thursday}">{$_TR.thursday_short}</th>
			<th scope="col" abbr="{$_TR.friday}" title="{$_TR.friday}">{$_TR.friday_short}</th>
			<th scope="col" abbr="{$_TR.saturday}" title="{$_TR.saturday}">{$_TR.saturday_short}</th>
			{if $smarty.const.DISPLAY_START_OF_WEEK != 0}
				<th scope="col" abbr="{$_TR.sunday}" title="{$_TR.sunday}">{$_TR.sunday_short}</th>
			{/if}
		</tr>
		{foreach from=$days item=week key=weekid}
			<tr class="{if $currentweek == $weekid}weblog_currentweek{/if}">
				{foreach from=$week key=day item=dayinfo}
					<td align="center">
					{if $dayinfo.number > -1}
						{if $dayinfo.number == 0}
							{$day}
						{else}
							<a class="mngmntlink weblog_mngmntlink" href="{link action=view_day day=$dayinfo.ts}" title="{$dayinfo.ts|format_date:"%A, %B %e, %Y"}" alt="{$dayinfo.ts|format_date:"%A, %B %e, %Y"}"><b>{$day}</b></a>
						{/if}
					{else}
						&nbsp;
					{/if}
					</td>
				{/foreach}
			</tr>
		{/foreach}
	</table>
</div>
