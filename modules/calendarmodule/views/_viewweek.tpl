{*
 *
 * Copyright 2004 James Hunt and OIC Group, Inc.
 *
 * This file is part of Exponent
 *
 * Exponent is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * Exponent is distributed in the hope that it
 * will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR
 * PURPOSE.  See the GNU General Public License
 * for more details.
 *
 * You should have received a copy of the GNU
 * General Public License along with Exponent; if
 * not, write to:
 *
 * Free Software Foundation, Inc.,
 * 59 Temple Place,
 * Suite 330,
 * Boston, MA 02111-1307  USA
 *
 * $Id$
 *}
<a class="mngmntlink calendar_mngmntlink" href="{link action=viewweek time=$startprevweek}"><img border="0" src="{$smarty.const.ICON_RELATIVE}left.gif" title="Previous Week" alt="Previous Week" /></a>
<span style="font-weight: bold; font-size: 16px">Events for the week of {$startweek|format_date:"%B %e, %Y"}</span>
<a class="mngmntlink calendar_mngmntlink" href="{link action=viewweek time=$startnextweek}"><img border="0" src="{$smarty.const.ICON_RELATIVE}right.gif" title="Next Week" alt="Next Week" /></a>
<br />
<a class="mngmntlink calendar_mngmntlink" href="{link action=viewmonth time=$startweek}" title="View Entire Month" alt="View Entire Month">View Month</a>
<table cellpadding="2" cellspacing="0" width="100%" border="0">
{foreach from=$days item=events key=ts}
	<tr><td>
		<hr size="1"/>
		<b>
		{if $counts[$ts] != 0}
		<a class="mngmntlink calendar_mngmntlink" href="{link action=viewday time=$ts}">{$ts|format_date:"%A, %b %e"}</a>
		{else}
		{$ts|format_date:"%A, %b %e"}
		{/if}
		</b>
	</td></tr>
	{assign var=none value=1}
	{foreach from=$events item=event}
		{assign var=none value=0}
		<tr><td style="padding-left: 15px">
			<a class="mngmntlink calendar_mngmntlink" href="{link action=view id=$event->id date_id=$event->eventdate->id}">{$event->title}</a>
			{if $permissions.administrate == 1 || $event->permissions.administrate == 1}
				<a href="{link action=userperms int=$event->id _common=1}"><img border="0" src="{$smarty.const.ICON_RELATIVE}userperms.gif" title="Assign user permissions on this Calendar Event" alt="Assign user permissions on this Calendar Event" /></a>&nbsp;
				<a href="{link action=groupperms int=$event->id _common=1}"><img border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.gif" title="Assign group permissions on this Calendar Event" alt="Assign group permissions on this Calendar Event" /></a>
			{/if}
			{if $permissions.edit == 1 || $event->permissions.edit == 1}
				{if $event->approved == 1}
				<a href="{link action=edit id=$event->id date_id=$event->eventdate->id}"><img border="0" src="{$smarty.const.ICON_RELATIVE}edit.gif" title="Edit this Calendar Event" alt="Edit this Calendar Event" /></a>&nbsp;
				{else}
				<img border="0" src="{$smarty.const.ICON_RELATIVE}edit.disabled.gif" title="Editting Disabled - Calendar Event In Approval" alt="Editting Disabled - Calendar Event In Approval" />
				{/if}
			{/if}
			{if $permissions.delete == 1 || $event->permissions.delete == 1}
				{if $event->approved == 1}
					{if $event->is_recurring == 0}
					<a href="{link action=delete id=$event->id}" onClick="return confirm('Are you sure you want to delete this Calendar Event?');"><img border="0" src="{$smarty.const.ICON_RELATIVE}delete.gif" title="Delete this Calendar Event" alt="Delete this Calendar Event" /></a>
					{else}
					<a href="{link action=delete_form date_id=$event->eventdate->id id=$event->id}"><img border="0" src="{$smarty.const.ICON_RELATIVE}delete.gif" title="Delete this Calendar Event" alt="Delete this Calendar Event" /></a>
					{/if}
				{else}
				<img border="0" src="{$smarty.const.ICON_RELATIVE}delete.disabled.gif" title="Deleting Disabled - Calendar Event In Approval" alt="Deleting Disabled - Calendar Event In Approval" />
				{/if}
			{/if}
			{if $permissions.manage_approval == 1}
				<a class="mngmntlink calendar_mngmntlink" href="{link module=workflow datatype=calendar m=calendarmodule s=$__loc->src action=revisions_view id=$event->id}" title="View Revision History for this Calendar Event" alt="View Revision History for this Calendar Event">Revisions</a>
			{/if}
			<div style="padding-left: 10px">
				<b>{if $event->is_allday == 1}All Day{else}
				{$event->eventstart|format_date:"%l:%M %P"} - {$event->eventend|format_date:"%l:%M %P"}
				{/if}</b><br />
				{$event->summary}
			</div>
		</td></tr>
	{/foreach}
	{if $none == 1}
		<tr><td><i>No Events</i></td></tr>
	{/if}
	<tr><td></td></tr>
{/foreach}
</table>