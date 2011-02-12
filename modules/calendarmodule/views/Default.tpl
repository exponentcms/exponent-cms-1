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
	<div class="itemactions">
		<span class="monthviewlink">{$_TR.calendar_view}</span>&nbsp;&nbsp;|&nbsp;&nbsp;<a class="listviewlink" href="{link _common=1 view='Monthly List' action='show_view' time=$time}">{$_TR.list_view}</a>
		{permissions level=$smarty.const.UILEVEL_NORMAL}
			{if $permissions.administrate == 1}
				&nbsp;&nbsp;|&nbsp;&nbsp;<a class="adminviewlink mngmntlink" href="{link _common=1 view='Administration' action='show_view' time=$time}">{$_TR.administration_view}</a>
			{/if}
			&nbsp;&nbsp;|&nbsp;&nbsp;
			{printer_friendly_link class="printer-friendly-link" text=$_TR.printer_friendly}
			{br}
			{if $permissions.post == 1}
				{br}
				<a class="addevent mngmntlink" href="{link action=edit id=0}" title="{$_TR.alt_create}">{$_TR.create}</a>
			{/if}
			{if $in_approval != 0 && $canview_approval_link == 1}
				{br}
				<a class="mngmntlink calendar_mngmntlink" href="{link module=workflow datatype=calendar m=calendarmodule s=$__loc->src action=summary}" title="{$_TR.alt_approval}">{$_TR.view_approval}</a>
			{/if}
			{if $config->enable_categories == 1}
				{br}
				{if $permissions.manage_categories == 1}
					<a href="{link module=categories orig_module=calendarmodule action=manage}" class="mngmntlink cats">{$_TR.manage_categories}</a>
				{else}
					<a class="cats" href="#" onclick="window.open('{$smarty.const.PATH_RELATIVE}popup.php?module=categories&amp;m={$__loc->mod}&amp;action=view&amp;src={$__loc->src}','legend','width=200,height=200,title=no,status=no'); return false" title="{$_TR.alt_view_cat}">{$_TR.view_categories}</a>
				{/if}
			{/if}
		{/permissions}
	</div>
	<h2>
		{if $enable_ical == true}
			<a class="icallink itemactions" href="{link action=ical}" title="{$_TR.alt_ical}" alt="{$_TR.alt_ical}">{$_TR.ical}</a>
		{/if}
		{if $moduletitle != ""}{$moduletitle}{/if}
	</h2>

	<table id="calendar" summary="{$moduletitle|default:$_TR.default_summary}">
	<caption>
	<a class="mngmntlink calendar_mngmntlink" href="{link action=viewmonth time=$prevmonth}"><img class="mngmnt_icon itemactions" style="border:none;" src="{$smarty.const.ICON_RELATIVE}left.png" title="{$_TR.previous}" alt="{$_TR.previous}" /></a>
	<b>{$time|format_date:"%B %Y"}</b>
	<a class="mngmntlink calendar_mngmntlink" href="{link action=viewmonth time=$nextmonth}"><img class="mngmnt_icon itemactions" style="border:none;" src="{$smarty.const.ICON_RELATIVE}right.png" title="{$_TR.next}" alt="{$_TR.next}" /></a>
	</caption>

		<tr class="daysoftheweek">
			{if $smarty.const.DISPLAY_START_OF_WEEK == 0}
			<th scope="col" abbr="{$_TR.sunday}" title="{$_TR.sunday}">{$_TR.sunday}</th>
			{/if}
			<th scope="col" abbr="{$_TR.monday}" title="{$_TR.monday}">{$_TR.monday}</th>
			<th scope="col" abbr="{$_TR.tuesday}" title="{$_TR.tuesday}">{$_TR.tuesday}</th>
			<th scope="col" abbr="{$_TR.wednesday}" title="{$_TR.wednesday}">{$_TR.wednesday}</th>
			<th scope="col" abbr="{$_TR.thursday}" title="{$_TR.thursday}">{$_TR.thursday}</th>
			<th scope="col" abbr="{$_TR.friday}" title="{$_TR.friday}">{$_TR.friday}</th>
			<th scope="col" abbr="{$_TR.saturday}" title="{$_TR.saturday}">{$_TR.saturday}</th>
			{if $smarty.const.DISPLAY_START_OF_WEEK != 0}
			<th scope="col" abbr="{$_TR.sunday}" title="{$_TR.sunday}">{$_TR.sunday}</th>
			{/if}
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
				<td {if $dayts == $today}class="today" {elseif $number == -1}class="notinmonth" {else}class="oneday" {/if}>
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
						{if $__viewconfig.colorize == 1 && $config->enable_categories}{assign var=catid value=$event->category_id}{/if}
						<div class="calevent">
						<a class="mngmntlink calendar_mngmntlink" href="{link action=view id=$event->id date_id=$event->eventdate->id}"{if $catid != 0} style="color: {$categories[$catid]->color};"{/if}
							title="{if $event->is_allday == 1}All Day{elseif $event->eventstart != $event->eventend}{$event->eventstart|format_date:$smarty.const.DISPLAY_TIME_FORMAT} to {$event->eventend|format_date:$smarty.const.DISPLAY_TIME_FORMAT}{else}{$event->eventstart|format_date:$smarty.const.DISPLAY_TIME_FORMAT}{/if} - {$event->body|summarize:"html":"para"}">{$event->title}</a>
						<div class="itemactions">
							{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
								{if $permissions.administrate == 1 || $event->permissions.administrate == 1}
									<a class="mngmntlink calendar_mngmntlink" href="{link action=userperms int=$event->id _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm_one}" alt="{$_TR.alt_userperm_one}" /></a>
									<a class="mngmntlink calendar_mngmntlink" href="{link action=groupperms int=$event->id _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm_one}" alt="{$_TR.alt_groupperm_one}" /></a>
								{/if}
							{/permissions}
							{permissions level=$smarty.const.UILEVEL_NORMAL}
								{if $permissions.edit == 1 || $event->permissions.edit == 1}
									{if $event->approved == 1}
										<a class="mngmntlink calendar_mngmntlink" href="{link action=edit id=$event->id date_id=$event->eventdate->id}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>
									{else}
										<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.disabled.png" title="{$_TR.alt_edit_disabled}" alt="{$_TR.alt_edit_disabled}" />
									{/if}
								{/if}
								{if $permissions.delete == 1 || $event->permissions.delete == 1}
									{if $event->approved == 1}
										{if $event->is_recurring == 0}
											<a class="mngmntlink calendar_mngmntlink" href="{link action=delete id=$event->id}" onclick="return confirm('{$_TR.delete_confirm}');"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" /></a>
										{else}
											<a class="mngmntlink calendar_mngmntlink" href="{link action=delete_form id=$event->id date_id=$event->eventdate->id}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" /></a>
										{/if}
									{else}
										<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.disabled.png" title="{$_TR.alt_delete_disabled}" alt="{$_TR.alt_delete_disabled}" />
									{/if}
								{/if}
								{if $permissions.manage_approval == 1}
									<a class="mngmntlink calendar_mngmntlink" href="{link module=workflow datatype=calendar m=calendarmodule s=$__loc->src action=revisions_view id=$event->id}" title="{$_TR.alt_revisions}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}revisions.png" title="{$_TR.alt_revisions}" alt="{$_TR.alt_revisions}"/></a>
								{/if}
							{/permissions}
						</div>	
						</div>						
					{/foreach}				
				</td>
			{/foreach}
			</tr>
			{/if}
		{/foreach}
	</table>
</div>
