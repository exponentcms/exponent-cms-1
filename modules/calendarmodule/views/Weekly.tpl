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
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="Assign user permissions on this Calendar" alt="Assign user permissions on this Calendar" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="Assign group permissions on this Calendar" alt="Assign group permissions on this Calendar" /></a>
{/if}
{if $permissions.configure == 1}
	<a href="{link action=configure _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}configure.png" title="Change the configuration of this Calendar" alt="Change the configuration of this Calendar" /></a>
{/if}
{/permissions}
<table width="177" border="0" cellpadding="0" cellspacing="0" bordercolor="666666">
                        <tr>
                                <td align="left" valign="top" bgcolor="99999">
                                        <table width="177" border="0" cellpadding="0" cellspacing="0" bgcolor="#999999">
                                <tr>
                                                        <td width="20" align="left" valign="top"><img class="mngmnt_icon" src="{$smarty.const.PATH_RELATIVE}modules/calendarmodule/images/topcurve.gif" width="20" height="20" /></td>
                                                        <td width="132" class="moduletitle calendar_highlights_moduletitle">{if $moduletitle != ""}<div align="center">{$moduletitle}</div>{/if}</td>
                                                        <td width="20"></td>
                                                </tr>
                                        </table>
                                </td>
                        </tr>
                        <tr>
                                <td height="28" align="left" valign="top" bordercolor="999999">
                                        <table width="177" border="0" cellpadding="0" cellspacing="0" bordercolor="#999999">
                                                <tr>
                                                        <td width="3"  bgcolor="#999999"></td>
                                                        <td align="left" valign="top">
                                                                <table width="176" border="0" cellspacing="5" cellpadding="0">
                                                                        <tr>
                                                                                <td align="left" valign="top">
{foreach from=$days item=events key=ts}
	<div class="sectiontitle">
	<b>{$ts|format_date:"%A, %b %e"}</b>
	</div>
	{assign var=none value=1}
	{foreach from=$events item=event}
		{assign var=none value=0}
		<div class="paragraph">
		{if $event->is_allday == 0}{$event->eventstart|format_date:"%l:%M %P"}{/if}
		<a class="mngmntlink calendar_mngmntlink" href="{link action=view id=$event->id date_id=$event->eventdate->id}">{$event->title}</a>
		{if $permissions.edit == 1 || $event->permissions.edit == 1 || $permissions.delete == 1 || $event->permissions.delete == 1 || $permissions.administrate == 1 || $event->permissions.administrate == 1}
		<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		{/if}
		{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
		{if $permissions.administrate == 1 || $event->permissions.administrate == 1}
		<a class="mngmntlink calendar_mngmntlink" href="{link action=userperms int=$event->id _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="Assign user permissions on this Calendar Event" alt="Assign user permissions on this Calendar Event" /></a>
		<a class="mngmntlink calendar_mngmntlink" href="{link action=groupperms int=$event->id _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="Assign group permissions on this Calendar Event" alt="Assign group permissions on this Calendar Event" /></a>
		{/if}
		{/permissions}
		{permissions level=$smarty.const.UILEVEL_NORMAL}
		{if $permissions.edit == 1 || $event->permissions.edit == 1}
			{if $event->approved == 1}
			<a class="mngmntlink calendar_mngmntlink" href="{link action=edit id=$event->id date_id=$event->eventdate->id}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}edit.png" title="Edit this Calendar Event" alt="Edit this Calendar Event" /></a>
			{else}
			<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}edit.disabled.png" title="Editting Disabled - Calendar Event In Approval" alt="Editting Disabled - Calendar Event In Approval" />
			{/if}
		{/if}
		{if $permissions.delete == 1 || $event->permissions.delete == 1}
			{if $event->approved == 1}
			{if $event->is_recurring == 0}
			<a class="mngmntlink calendar_mngmntlink" href="{link action=delete id=$event->id}" onClick="return confirm('Are you sure you want to delete this Calendar Event?');"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.png" title="Delete this Calendar Event" alt="Delete this Calendar Event" /></a>
			{else}
			<a class="mngmntlink calendar_mngmntlink" href="{link action=delete_form id=$event->id date_id=$event->eventdate->id}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.png" title="Delete this Calendar Event" alt="Delete this Calendar Event" /></a>
			{/if}
			{else}
			<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.disabled.png" title="Deleting Disabled - Calendar Event In Approval" alt="Deleting Disabled - Calendar Event In Approval" />
			{/if}
		{/if}
		{if $permissions.manage_approval == 1}
			<a class="mngmntlink calendar_mngmntlink" href="{link module=workflow datatype=calendar m=calendarmodule s=$__loc->src action=revisions_view id=$event->id}" title="View Revision History for this Calendar Event" alt="View Revision History for this Calendar Event">Revisions</a>
		{/if}
		{/permissions}
		<br />
	{/foreach}
	{if $none == 1}
		<div class="paragraph"><i>No Events.</i></div>
	{/if}
	<br />
{/foreach}
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.post == 1}
<a class="mngmntlink calendar_mngmntlink" href="{link action=edit id=0}" title="Create a new Calendar Event" alt="Create a new Calendar Event">Create Event</a><br />
{/if}
{if $in_approval != 0 && $canview_approval_link == 1}
<a class="mngmntlink calendar_mngmntlink" href="{link module=workflow datatype=calendar m=calendarmodule s=$__loc->src action=summary}" title="View Calendar Events in Approval" alt="View Calendar Events in Approval">View Approval</a>
{/if}
{/permissions}
                                                                                </td>
                                                                        </tr>
                                                                </table>
                                                        </td>
                                                </tr>
                                        </table>
                                </td>
                        </tr>
                        <tr>
                                <td height="10" align="left" valign="top" bgcolor="999999"><img class="mngmnt_icon" src="{$smarty.const.PATH_RELATIVE}modules/calendarmodule/images/bottomcurve.gif" width="10" height="10" /></td>
                        </tr>
                </table>

{if $modconfig->enable_categories == 1}
<a href="{link module=categories m=calendarmodule action=manage}" class="mngmntlink calendar_mngmntlink">Manage Categories</a>
{/if}
        <br />
        <br />
