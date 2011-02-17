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
		<a class="weekviewlink" href="{link action=viewweek time=$now view=_viewweek}" title="{$_TR.alt_view_week}">{$_TR.view_week}</a>&nbsp;&nbsp;|&nbsp;&nbsp;
		<a class="monthviewlink" href="{link action=viewmonth time=$item->eventstart}" title="{$_TR.alt_view_month}" alt="{$_TR.alt_view_month}">{$_TR.view_month}</a>&nbsp;&nbsp;|&nbsp;&nbsp;
		{printer_friendly_link class="printer-friendly-link" text=$_TR.printer_friendly}
	</div>
	<h2>
	{if $enable_ical == true}
		<a class="icallink itemactions" href="{link action=ical}" title="{$_TR.alt_ical}" alt="{$_TR.alt_ical}">{$_TR.ical}</a>
	{/if}
	{if $moduletitle != ""}{$moduletitle}{/if}
	</h2>
	<p class="caption">
		<a class="itemactions calendar_mngmntlink" href="{link action=viewday time=$prevday3}" title="{$prevday3|format_date:"%A, %B %e, %Y"}">{$prevday3|format_date:"%a"}</a>&nbsp;&nbsp;&laquo;&nbsp;
		<a class="itemactions calendar_mngmntlink" href="{link action=viewday time=$prevday2}" title="{$prevday2|format_date:"%A, %B %e, %Y"}">{$prevday2|format_date:"%a"}</a>&nbsp;&nbsp;&laquo;&nbsp;
		<a class="itemactions calendar_mngmntlink" href="{link action=viewday time=$prevday}" title="{$prevday|format_date:"%A, %B %e, %Y"}">{$prevday|format_date:"%a"}</a>&nbsp;&nbsp;&laquo;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<span>{$now|format_date:"%A, %B %e, %Y"}</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&nbsp;&nbsp;
		<a class="itemactions calendar_mngmntlink" href="{link action=viewday time=$nextday}" title="{$nextday|format_date:"%A, %B %e, %Y"}">{$nextday|format_date:"%a"}</a>&nbsp;&nbsp;&raquo;&nbsp;
		<a class="itemactions calendar_mngmntlink" href="{link action=viewday time=$nextday2}" title="{$nextday2|format_date:"%A, %B %e, %Y"}">{$nextday2|format_date:"%a"}</a>&nbsp;&nbsp;&raquo;&nbsp;
		<a class="itemactions calendar_mngmntlink" href="{link action=viewday time=$nextday3}" title="{$nextday3|format_date:"%A, %B %e, %Y"}">{$nextday3|format_date:"%a"}</a>
	</p>
	<dl class="viewweek">
		{assign var=count value=0}
		{foreach from=$events item=event}
			{assign var=count value=1}
			<dt>
				<div class="itemactions">
					{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
						{if $permissions.administrate == 1 || $event->permissions.administrate == 1}
							<a href="{link action=userperms int=$event->id _common=1}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>&nbsp;
							<a href="{link action=groupperms int=$event->id _common=1}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
						{/if}
						{if $permissions.edit == 1 || $event->permissions.edit == 1}
							{if $event->approved == 1}
								<b><a href="{link action=edit id=$event->id date_id=$event->eventdate->id}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a></b>&nbsp;
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
						{if $permissions.administrate == 1 || $event->permissions.administrate == 1 || 
							$permissions.edit == 1 || $event->permissions.edit == 1 ||
							$permissions.delete == 1 || $event->permissions.delete == 1 || $permissions.manage_approval == 1}
							{br}
						{/if}
					{/permissions}
				</div>
				<span class="eventtitle"><a class="itemtitle calendar_mngmntlink" href="{link action=view id=$event->id date_id=$event->eventdate->id}"><b>{$event->title}</b></a></span>
				{if $event->image_path}
					<span class="eventimg"><img src="{$smarty.const.URL_FULL}{$event->image_path}" alt="{$event->title}" /></span>
				{/if}		
			</dt>
			<dd>
				<p>
					<span><b>
						{if $event->is_allday == 1}{$_TR.all_day}{else}
							{if $event->eventstart != $event->eventend}
								{$event->eventstart|format_date:$smarty.const.DISPLAY_TIME_FORMAT} to {$event->eventend|format_date:$smarty.const.DISPLAY_TIME_FORMAT}
							{else}
								{$event->eventstart|format_date:$smarty.const.DISPLAY_TIME_FORMAT}
							{/if}
						{/if}
					</b></span>
					{br}
					{$event->body|summarize:"html":"paralinks"}
				</p>
			</dd>
		{/foreach}
		{if $count == 0}
			<dd><em>{$_TR.no_events}</em></dd>
		{/if}
	</dl>
</div>