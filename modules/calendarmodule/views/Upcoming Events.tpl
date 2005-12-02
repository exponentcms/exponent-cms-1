{*
 * Copyright (c) 2004-2005 OIC Group, Inc.
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
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="Assign user permissions on this Calendar" alt="Assign user permissions on this Calendar" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="Assign group permissions on this Calendar" alt="Assign group permissions on this Calendar" /></a>
{/if}
{if $permissions.configure == 1}
	<a href="{link action=configure _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}configure.png" title="Change the configuration of this Calendar" alt="Change the configuration of this Calendar" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}
{if $moduletitle != ""}<div class="moduletitle calendar_moduletitle">{$moduletitle}</div>{/if}
<table cellspacing="0" cellpadding="4" border="0" width="100%">
{foreach from=$items item=item}
	<tr>
		<td><a class="mngmntlink calendar_mngmntlink" href="{link action=view id=$item->id date_id=$item->eventdate->id}">{$item->title}</a></td>
		<td align="right">
			{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
				{if $permissions.administrate == 1 || $item->permissions.administrate == 1}
					<a class="mngmntlink calendar_mngmntlink" href="{link action=userperms int=$item->id _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="Assign user permissions on this Calendar Event" alt="Assign user permissions on this Calendar Event" /></a>
					<a class="mngmntlink calendar_mngmntlink" href="{link action=groupperms int=$item->id _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="Assign group permissions on this Calendar Event" alt="Assign group permissions on this Calendar Event" /></a>
				{/if}
			{/permissions}
			{permissions level=$smarty.const.UILEVEL_NORMAL}
				{if $permissions.edit == 1 || $item->permissions.edit == 1}
					{if $item->approved == 1}
					<a class="mngmntlink calendar_mngmntlink" href="{link action=edit id=$item->id date_id=$item->eventdate->id}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}edit.png" title="Edit this Calendar Event" alt="Edit this Calendar Event" /></a>
					{else}
					<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}edit.disabled.png" title="Editting Disabled - Calendar Event In Approval" alt="Editting Disabled - Calendar Event In Approval" />
					{/if}
				{/if}
				{if $permissions.delete == 1 || $item->permissions.delete == 1}
					{if $item->approved == 1}
					{if $item->is_recurring == 0}
					<a class="mngmntlink calendar_mngmntlink" href="{link action=delete id=$item->id}" onClick="return confirm('Are you sure you want to delete this Calendar Event?');"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.png" title="Delete this Calendar Event" alt="Delete this Calendar Event" /></a>
					{else}
					<a class="mngmntlink calendar_mngmntlink" href="{link action=delete_form id=$item->id date_id=$item->eventdate->id}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.png" title="Delete this Calendar Event" alt="Delete this Calendar Event" /></a>
					{/if}
					{else}
					<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.disabled.png" title="Deleting Disabled - Calendar Event In Approval" alt="Deleting Disabled - Calendar Event In Approval" />
					{/if}
				{/if}
				{if $permissions.manage_approval == 1}
					<a class="mngmntlink calendar_mngmntlink" href="{link module=workflow datatype=calendar m=calendarmodule s=$__loc->src action=revisions_view id=$item->id}" title="View Revision History for this Calendar Event" alt="View Revision History for this Calendar Event">Revisions</a>
				{/if}
			{/permissions}
		</td>
	</tr>
	<tr>
		<td colspan="2">{if $item->is_allday == 1}{$item->eventstart|format_date:$smarty.const.DISPLAY_DATE_FORMAT}{else}{$item->eventstart|format_date:"%a %b %e, %l:%M %P"} - {$item->eventend|format_date:"%l:%M %P"}{/if}</td>
	</tr>
	<tr>
		<td colspan="2">
			{$item->body|summarize:html:para}
		</td>
	</tr>
{foreachelse}
	<tr><td align="center"><i>No upcoming events.</i></td></tr>
{/foreach}
</table>
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.post == 1}
<a class="mngmntlink calendar_mngmntlink" href="{link action=edit id=0}" title="Create a new Calendar Event" alt="Create a new Calendar Event">Create Event</a>
{/if}
<br />
{if $in_approval != 0 && $canview_approval_link == 1}
<a class="mngmntlink calendar_mngmntlink" href="{link module=workflow datatype=calendar m=calendarmodule s=$__loc->src action=summary}" title="View Calendar Events in Approval" alt="View Calendar Events in Approval">View Approval</a>
{/if}
{if $modconfig->enable_categories == 1}
{if $permissions.administrate == 1}
<br />
<a href="{link module=categories orig_module=calendarmodule action=manage}" class="mngmntlink calendar_mngmntlink">Manage Categories</a>
{else}
<br />
<a class="mngmntlink calendar_mngmntlink" href="#" onClick="window.open('{$smarty.const.PATH_RELATIVE}/popup.php?module=categories&m={$__loc->mod}&action=view&src={$__loc->src}','legend','width=200,height=200,title=no,status=no'); return false" title="View Event Categories" alt="View Event Categories">View Categories</a>
{/if}
{/if}
{/permissions}