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
<a href="{link _common=1 view=Monthly action=show_view time=$time}">Calendar View</a>&nbsp;&nbsp;|&nbsp;&nbsp;List View<br /><br />
<a class="mngmntlink calendar_mngmntlink" href="{link action=show_view _common=1 view='Monthly List' time=$prev_timestamp}"><img border="0" src="{$smarty.const.ICON_RELATIVE}left.gif"/></a>
<b>Month of {$time|format_date:"%B %Y"}</b>
<a class="mngmntlink calendar_mngmntlink" href="{link action=show_view _common=1 view='Monthly List' time=$next_timestamp}"><img border="0" src="{$smarty.const.ICON_RELATIVE}right.gif"/></a>
<br /><br />
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}" title="Assign permissions on this Calendar Module" alt="Assign permissions on this Calendar Module"><img border="0" src="{$smarty.const.ICON_RELATIVE}userperms.gif" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}" title="Assign group permissions on this Calendar Module" alt="Assign group permissions on this Calendar Module"><img border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.gif" /></a>
{/if}
{if $permissions.configure == 1}
	<a href="{link action=configure _common=1}" title="Configure this Calendar Module" alt="Configure this Calendar Module"><img border="0" src="{$smarty.const.ICON_RELATIVE}configure.gif" /></a>
{/if}
{/permissions}
{foreach from=$days item=events key=ts}
	{if_elements array=$events}
	<div class="sectiontitle">
	{$ts|format_date:"%A, %b %e"}
	</div>
	{assign var=none value=1}
	{foreach from=$events item=event}
		{assign var=none value=0}
		<div class="paragraph">
		{if $event->is_allday}{$event->eventstart|format_date:"%I:%M %P"}{/if}
		<a class="mngmntlink calendar_mngmntlink" href="{link action=view id=$event->id date_id=$event->eventdate->id}">{$event->title}</a>
		{if $permissions.edit == 1 || $event->permissions.edit == 1 || $permissions.delete == 1 || $event->permissions.delete == 1 || $permissions.administrate == 1 || $event->permissions.administrate == 1}
		<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		{/if}
		{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
		{if $permissions.administrate == 1 || $event->permissions.administrate == 1}
		<a class="mngmntlink calendar_mngmntlink" href="{link action=userperms int=$event->id _common=1}"><img border="0" src="{$smarty.const.ICON_RELATIVE}userperms.gif" title="Assign user permissions for this event" alt="Assign user permissions for this event" /></a>
		<a class="mngmntlink calendar_mngmntlink" href="{link action=groupperms int=$event->id _common=1}"><img border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.gif" title="Assign group permissions for event" alt="Assign group permissions for event" /></a>
		{/if}
		{/permissions}
		{permissions level=$smarty.const.UILEVEL_NORMAL}
		{if $permissions.edit == 1 || $event->permissions.edit == 1}
			{if $event->approved == 1}
			<a class="mngmntlink calendar_mngmntlink" href="{link action=edit id=$event->id date_id=$event->eventdate->id}" title="Edit this event" alt="Edit this event"><img border="0" src="{$smarty.const.ICON_RELATIVE}edit.gif" /></a>
			{else}
			<img border="0" src="{$smarty.const.ICON_RELATIVE}edit.disabled.gif" title="Editting Disabled - Content In Approval" alt="Editting Disabled - Content In Approval" />
			{/if}
		{/if}
		{if $permissions.delete == 1 || $event->permissions.delete == 1}
			{if $event->approved == 1}
			{if $event->is_recurring == 0}
			<a class="mngmntlink calendar_mngmntlink" href="{link action=delete id=$event->id}" onClick="return confirm('Are you sure you want to delete this event?');" title="Delete this event" alt="Delete this event"><img border="0" src="{$smarty.const.ICON_RELATIVE}delete.gif" /></a>
			{else}
			<a class="mngmntlink calendar_mngmntlink" href="{link action=delete_form id=$event->id date_id=$event->eventdate->id}" onClick="return confirm('Are you sure you want to delete this event?');" title="Delete this event" alt="Delete this event"><img border="0" src="{$smarty.const.ICON_RELATIVE}delete.gif" /></a>
			{/if}
			{else}
			<img border="0" src="{$smarty.const.ICON_RELATIVE}delete.disabled.gif" title="Deleting Disabled - Content In Approval" />
			{/if}
		{/if}
		{if $permissions.manage_approval == 1}
			<a class="mngmntlink calendar_mngmntlink" href="{link module=workflow datatype=calendar m=calendarmodule s=$__loc->src action=revisions_view id=$event->id}">
				Revisions
			</a>
		{/if}
		{/permissions}
		</div>
		<br />
	{/foreach}
	{if $none == 1}
		<div class="paragraph"><strong>No Events.</strong></div>
	{/if}
	<br />
	{/if_elements}
{/foreach}
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.post == 1}
<a class="mngmntlink calendar_mngmntlink" href="{link action=edit id=0}">Create Event</a><br />
{/if}
{if $in_approval != 0 && $canview_approval_link == 1}
<a class="mngmntlink calendar_mngmntlink" href="{link module=workflow datatype=calendar m=calendarmodule s=$__loc->src action=summary}">View Approval</a>
{/if}
{/permissions}

{if $modconfig->enable_categories == 1}
<a href="{link module=categories m=calendarmodule action=manage}" class="mngmntlink calendar_mngmntlink">Manage Categories</a>
{/if}