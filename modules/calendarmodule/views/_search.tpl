{*
 *
 * Copyright (c) 2004-2005 OIC Group, Inc.
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
 * $Id: _viewday.tpl,v 1.1.1.1 2005/07/14 18:34:04 cvs Exp $
 *}
<b>{$title}</b><br/>
<table cellpadding="0" valign="top" cellspacing="0" width="100%" border="0">
{foreach from=$days item=event}
<tr><td><strong>{$event->eventdate|format_date:"%A, %B %e, %Y"}</strong><hr size="1" /></td></tr>
{foreach from=$event->dates item=dates}
	<tr><td style="padding-left: 15px">
		{if $permissions.administrate == 1 || $event->permissions.administrate == 1}
			<a href="{link action=userperms int=$event->id _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>&nbsp;
			<a href="{link action=groupperms int=$event->id _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
		{/if}
		{if $permissions.edit == 1 || $event->permissions.edit == 1}
			{if $event->approved == 1}
			<a href="{link action=edit id=$event->id date_id=$event->eventdate->id}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>&nbsp;
			{else}
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.disabled.png" title="{$_TR.alt_edit_disabled}" alt="{$_TR.alt_edit_disabled}" />
			{/if}
		{/if}
		{if $permissions.delete == 1 || $event->permissions.delete == 1}
			{if $event->approved == 1}
				{if $event->is_recurring == 0}
				<a href="{link action=delete id=$event->id}" onclick="return confirm('{$_TR.delete_confirm}');"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.delete}" alt="{$_TR.delete}" /></a>
				{else}
				<a href="{link action=delete_form date_id=$event->eventdate->id id=$event->id}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.delete}" alt="{$_TR.delete}" /></a>
				{/if}
			{else}
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.disabled.png" title="{$_TR.alt_delete_disabled}" alt="{$_TR.alt_delete_disabled}" />
			{/if}
		{/if}
		{if $permissions.manage_approval == 1}
			<a class="mngmntlink calendar_mngmntlink" href="{link module=workflow datatype=calendar m=calendarmodule s=$__loc->src action=revisions_view id=$event->id}" title="View Revision History for this Calendar Event" alt="View Revision History for this Calendar Event">Revisions</a>
		{/if}
		<div style="padding-left: 10px">
			<b>{if $event->is_allday == 1}All Day{else}
			{$dates->eventstart|format_date:"%l:%M %P"} - {$dates->eventend|format_date:"%l:%M %P"}
			{/if}</b><br/>
			{$event->body|summarize:"html":"para"}</br>
			&nbsp;&nbsp;<a class="mngmntlink calendar_mngmntlink" href="{link action=view id=$event->id date_id=$dates->id}">Click here for more information or to register.</a>
</div><br/>
	</td></tr>
{/foreach}
{/foreach}
{if $count == 0}
	<tr><td><i>No Events</i></td></tr>
{/if}
</table>
