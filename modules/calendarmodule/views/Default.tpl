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
<div class="calendarmodule cal-default">
{if $enable_rss == true}
	<a class="rsslink" href="{rsslink}">{$_TR.rss_feed}</a> {br}
{/if}
<a class="listviewlink" href="{link _common=1 view='Monthly List' action='show_view' time=$time}">{$_TR.list_view}</a>{br}
	{permissions level=$smarty.const.UILEVEL_NORMAL}
	{if $permissions.post == 1}
	<a class="addevent" href="{link action=edit id=0}" title="{$_TR.alt_create}">{$_TR.create}</a>
	{/if}
	{if $in_approval != 0 && $canview_approval_link == 1}
	{br}
	<a class="mngmntlink calendar_mngmntlink" href="{link module=workflow datatype=calendar m=calendarmodule s=$__loc->src action=summary}" title="{$_TR.alt_approval}">{$_TR.view_approval}</a>
	{/if}
	{if $modconfig->enable_categories == 1}
	{if $permissions.manage_categories == 1}
	{br}
	<a href="{link module=categories orig_module=calendarmodule action=manage}" class="mngmntlink calendar_mngmntlink">{$_TR.manage_categories}</a>
	{else}
	{br}
	<a class="mngmntlink calendar_mngmntlink" href="#" onclick="window.open('{$smarty.const.PATH_RELATIVE}popup.php?module=categories&amp;m={$__loc->mod}&amp;action=view&amp;src={$__loc->src}','legend','width=200,height=200,title=no,status=no'); return false" title="{$_TR.alt_view_cat}">{$_TR.view_categories}</a>
	{/if}
	{/if}
	{/permissions}

{if $moduletitle}<h1>{$moduletitle}</h1>{/if}

<table id="calendar" summary="{$moduletitle|default:$_TR.default_summary}">
<caption><a class="nav doublearrow" href="{link action=viewmonth time=$prevmonth}" title="{$_TR.alt_previous}">&laquo;</a> {$now|format_date:"%B %Y"} <a class="nav doublearrow" href="{link action=viewmonth time=$nextmonth}" title="{$_TR.alt_next}">&raquo;</a></caption>

		<tr class="daysoftheweek">
			<th scope="col" abbr="{$_TR.sunday}" title="{$_TR.sunday}">{$_TR.sunday}</th>
			<th scope="col" abbr="{$_TR.monday}" title="{$_TR.monday}">{$_TR.monday}</th>
			<th scope="col" abbr="{$_TR.tuesday}" title="{$_TR.tuesday}">{$_TR.tuesday}</th>
			<th scope="col" abbr="{$_TR.wednesday}" title="{$_TR.wednesday}">{$_TR.wednesday}</th>
			<th scope="col" abbr="{$_TR.thursday}" title="{$_TR.thursday}">{$_TR.thursday}</th>
			<th scope="col" abbr="{$_TR.friday}" title="{$_TR.friday}">{$_TR.friday}</th>
			<th scope="col" abbr="{$_TR.saturday}" title="{$_TR.saturday}">{$_TR.saturday}</th>
		</tr>
	{math equation="x-86400" x=$now assign=dayts}
	{foreach from=$monthly item=week key=weeknum}
		{assign var=moredata value=0}
		{foreach name=w from=$week key=day item=events}
			{assign var=number value=$counts[$weeknum][$day]}
			{if $number > -1}{assign var=moredata value=1}{/if}
		{/foreach}
		{if $moredata == 1}
		<tr class="week{if $currentweek == $weeknum} currentweek{/if}">
		{foreach name=w from=$week key=day item=events}
			{assign var=number value=$counts[$weeknum][$day]}
			<td {if $number == -1}class="notinmonth" {else}class="oneday" {/if}>
				{if $number != -1}{math equation="x+86400" x=$dayts assign=dayts}{/if}
				{if $number > -1}
					{if $number == 0}
						<span class="number">
							{$day}
						</span>
					{else}
						<span class="number">
							<a class="number" href="{link action=viewday time=$dayts}" title="{$dayts|format_date:'%A, %B %e, %Y'}">{$day}</a>
						</span>
					{/if}
				{/if}
				{foreach name=e from=$events item=event}
					{assign var=catid value=0}
					{if $__viewconfig.colorize == 1 && $modconfig->enable_categories}{assign var=catid value=$event->category_id}{/if}
					<a class="calevent mngmntlink calendar_mngmntlink" href="{link action=view id=$event->id date_id=$event->eventdate->id}"{if $catid != 0} style="color: {$categories[$catid]->color};"{/if} title="{$event->eventstart|format_date:$smarty.const.DISPLAY_TIME_FORMAT} to {$event->eventend|format_date:$smarty.const.DISPLAY_TIME_FORMAT}">{$event->title}</a>
				{/foreach}
			</td>
		{/foreach}
		</tr>
		{/if}
	{/foreach}
</table>
</div>
