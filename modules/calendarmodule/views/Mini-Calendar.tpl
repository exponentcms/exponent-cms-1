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
 {permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}"><img border="0" src="{$smarty.const.ICON_RELATIVE}userperms.gif" title="Assign user permissions on this Calendar" alt="Assign user permissions on this Calendar" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}"><img border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.gif" title="Assign group permissions on this Calendar" alt="Assign group permissions on this Calendar" /></a>
{/if}
{if $permissions.configure == 1}
	<a href="{link action=configure _common=1}"><img border="0" src="{$smarty.const.ICON_RELATIVE}configure.gif" title="Change the configuration of this Calendar" alt="Change the configuration of this Calendar" /></a>
{/if}
{/permissions}
<table cellspacing="0" cellpadding="2" border="0" width="160">
<tr><td align="center" class="calendar_header" colspan="7">{if $moduletitle != ""}{$moduletitle} {/if}{$now|date_format:"%B"}</td></tr>
	<tr>
		<td align="center" class="calendar_miniday">S</td>
		<td align="center" class="calendar_miniday">M</td>
		<td align="center" class="calendar_miniday">T</td>
		<td align="center" class="calendar_miniday">W</td>
		<td align="center" class="calendar_miniday">T</td>
		<td align="center" class="calendar_miniday">F</td>
		<td align="center" class="calendar_miniday">S</td>
	</tr>
{foreach from=$monthly item=week key=weekid}
	<tr class="{if $currentweek == $weekid}calendar_currentweek{/if}">
		{foreach from=$week key=day item=dayinfo}
			<td align="center">
			{if $dayinfo.number > -1}
				{if $dayinfo.number == 0}
					{$day}
				{else}
					<a class="mngmntlink calendar_mngmntlink" href="{link action=viewday time=$dayinfo.ts}" title="{$dayinfo.ts|date_format:"%A, %B %e, %Y"}" alt="{$dayinfo.ts|date_format:"%A, %B %e, %Y"}"><b>{$day}</b></a>
				{/if}
			{else}
				&nbsp;
			{/if}
			</td>
		{/foreach}
	</tr>
{/foreach}
</table>
<a class="mngmntlink calendar_mngmntlink" href="{link action=viewmonth}" title="View the month of {$now|date_format:"%B"}" alt="View the month of {$now|date_format:"%B"}">View Whole Month</a>
<br />
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.post == 1}
<a class="mngmntlink calendar_mngmntlink" href="{link action=edit}" title="Create a new Calendar Event" alt="Create a new Calendar Event">Create Event</a><br />
{/if}
{if $in_approval != 0 && $canview_approval_link == 1}
<a class="mngmntlink calendar_mngmntlink" href="{link module=workflow datatype=calendar m=calendarmodule s=$__loc->src action=summary}" title="View Calendar Events in Approval" alt="View Calendar Events in Approval">View Approval</a><br />
{/if}
{/permissions}
<br />