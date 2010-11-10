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
		<a class="monthviewlink" href="{link action=viewmonth time=$startweek}" title="{$_TR.alt_view_month}">{$_TR.view_month}</a>
		&nbsp;&nbsp;|&nbsp;&nbsp;{printer_friendly_link class="printer-friendly-link" text=$_TR.printer_friendly}
	</div>
	<h2>
	{if $enable_rss == true}
		<a href="{rsslink}"><img src="{$smarty.const.ICON_RELATIVE}rss-feed.gif" class="itemactions" title="{$_TR.alt_rssfeed}" alt="{$_TR.alt_rssfeed}" /></a>
	{/if}
	{if $moduletitle != ""}{$moduletitle}{/if}
	</h2>
	<p class="caption"><a class="mngmntlink calendar_mngmntlink" href="{link action=viewweek time=$startprevweek}"><img class="mngmnt_icon itemactions" src="{$smarty.const.ICON_RELATIVE}left.png" title="{$_TR.prev_week}" alt="{$_TR.prev_week}" /></a> <span>{$_TR.view_week} {$startweek|format_date:"%B %e, %Y"}</span> <a class="mngmntlink calendar_mngmntlink" href="{link action=viewweek time=$startnextweek}"><img class="mngmnt_icon itemactions" src="{$smarty.const.ICON_RELATIVE}right.png" title="{$_TR.next_week}" alt="{$_TR.next_week}" /></a></p>
	<dl class="viewweek">

		{foreach from=$days item=events key=ts}
			<dt>
				<strong>
				{if $counts[$ts] != 0}
					<a class="itemtitle calendar_mngmntlink" href="{link action=viewday time=$ts}">{$ts|format_date:"%A, %b %e"}</a>
				{else}
					{$ts|format_date:"%A, %b %e"}
				{/if}
				</strong>
			</dt>
			{assign var=none value=1}
			{foreach from=$events item=event}
				{assign var=none value=0}
				<dd>
					<div class="itemactions">
						{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
							{if $permissions.administrate == 1 || $event->permissions.administrate == 1}
								<a href="{link action=userperms int=$event->id _common=1}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>&nbsp;
								<a href="{link action=groupperms int=$event->id _common=1}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
							{/if}
							{if $permissions.edit == 1 || $event->permissions.edit == 1}
								{if $event->approved == 1}
									<a href="{link action=edit id=$event->id date_id=$event->eventdate->id}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>&nbsp;
								{else}
									<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.disabled.png" title="{$_TR.alt_edit_disabled}" alt="{$_TR.alt_edit_disabled}" />
								{/if}
							{/if}
							{if $permissions.delete == 1 || $event->permissions.delete == 1}
								{if $event->approved == 1}
									{if $event->is_recurring == 0}
										<a href="{link action=delete id=$event->id}" onclick="return confirm('{$_TR.delete_confirm}');"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" /></a>
									{else}
										<a href="{link action=delete_form date_id=$event->eventdate->id id=$event->id}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" /></a>
									{/if}
								{else}
									<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.disabled.png" title="{$_TR.alt_delete_disabled}" alt="{$_TR.alt_delete_disabled}" />
								{/if}
							{/if}
							{if $permissions.manage_approval == 1}
								<a class="mngmntlink calendar_mngmntlink" href="{link module=workflow datatype=calendar m=calendarmodule s=$__loc->src action=revisions_view id=$event->id}" title="{$_TR.alt_revisions}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}revisions.png" title="{$_TR.alt_revisions}" alt="{$_TR.alt_revisions}"/></a>
							{/if}
						{/permissions}
					</div>
					<a class="itemtitle calendar_mngmntlink" href="{link action=view id=$event->id date_id=$event->eventdate->id}" title="{$event->body|summarize:"html":"para"}">{$event->title}</a>
					<div>
						<strong>
							{if $event->is_allday == 1}All Day{else}
								{if $event->eventstart != $event->eventend}
									{$event->eventstart|format_date:$smarty.const.DISPLAY_TIME_FORMAT} to {$event->eventend|format_date:$smarty.const.DISPLAY_TIME_FORMAT}
								{else}
									{$event->eventstart|format_date:$smarty.const.DISPLAY_TIME_FORMAT}
								{/if}
							{/if}
						</strong>
						{br}
						{$event->summary}
					</div>
				</dd>
			{/foreach}
			
			{if $none == 1}
				<dd><em>{$_TR.no_events}</em></dd>
			{/if}
		{/foreach}
	</dl>
</div>
