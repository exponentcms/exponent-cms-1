{*
 *
 * Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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
{if $permissions.administrate == 1 || $item->permissions.administrate == 1}
	<a href="{link action=userperms int=$item->id _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="Assign user permissions on this Calendar Event" alt="Assign user permissions on this Calendar Event" /></a>&nbsp;
	<a href="{link action=groupperms int=$item->id _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="Assign group permissions on this Calendar Event" alt="Assign group permissions on this Calendar Event" /></a>
{/if}
{if $permissions.edit == 1 || $item->permissions.edit == 1}
	{if $item->approved == 1}
	<a href="{link action=edit id=$item->id date_id=$item->eventdate->id}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}edit.png" title="Edit this Calendar Event" alt="Edit this Calendar Event" /></a>&nbsp;
	{else}
	<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}edit.disabled.png" title="Editting Disabled - Calendar Event In Approval" alt="Editting Disabled - Calendar Event In Approval" />
	{/if}
{/if}
{if $permissions.delete == 1 || $item->permissions.delete == 1}
	{if $item->approved == 1}
		{if $item->is_recurring == 0}
		<a href="{link action=delete id=$item->id}" onClick="return confirm('Are you sure you want to delete this Calendar Event?');"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.png" title="Delete this Calendar Event" alt="Delete this Calendar Event" /></a>
		{else}
		<a href="{link action=delete_form date_id=$item->eventdate->id id=$item->id}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.png" title="Delete this Calendar Event" alt="Delete this Calendar Event" /></a>
		{/if}
	{else}
	<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.disabled.png" title="Deleting Disabled - Calendar Event In Approval" alt="Deleting Disabled - Calendar Event In Approval" />
	{/if}
{/if}
{if $permissions.manage_approval == 1}
	<a class="mngmntlink calendar_mngmntlink" href="{link module=workflow datatype=calendar m=calendarmodule s=$__loc->src action=revisions_view id=$item->id}" title="View Revision History for this Calendar Event" alt="View Revision History for this Calendar Event">Revisions</a>
{/if}
{if $permissions.administrate == 1 || $item->permissions.administrate == 1 || $permissions.delete == 1 || $item->permissions.delete == 1 || $permissions.edit == 1 || $item->permissions.edit == 1}
<br />
{/if}
<h3>{$item->title}</h3>
{if $item->is_allday == 1}
{$item->eventstart|format_date:$smarty.const.DISPLAY_DATE_FORMAT}, All Day
{else}
{$item->eventstart|format_date:$smarty.const.DISPLAY_DATE_FORMAT} {$item->eventstart|format_date:$smarty.const.DISPLAY_TIME_FORMAT} - {$item->eventend|format_date:$smarty.const.DISPLAY_TIME_FORMAT}
{/if}
<hr size="1" />
{$item->body}
{if $item->body}
<br />
<hr size="1" />
{/if}
{$form}
<a class="mngmntlink calendar_mngmntlink" href="{link action=viewweek time=$item->eventstart}" title="View Entire Week" alt="View Entire Week">View Week</a>&nbsp;|&nbsp;
<a class="mngmntlink calendar_mngmntlink" href="{link action=viewmonth time=$item->eventstart}" title="View Entire Month" alt="View Entire Month">View Month</a><br />