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
Calendar View&nbsp;&nbsp;|&nbsp;&nbsp;<a href="{link _common=1 view="Monthly List" action=show_view time=$time}">List View</a><br /><br />
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}" title="Assign permissions on this Calendar Module" alt="Assign permissions on this Calendar Module"><img border="0" src="{$smarty.const.ICON_RELATIVE}userperms.gif" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}" title="Assign group permissions on this Calendar Module" alt="Assign group permissions on this Calendar Module"><img border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.gif" /></a>
{/if}
{if $permissions.configure == 1}
	<a href="{link action=configure _common=1}" title="Configure this Calendar Module" alt="Configure this Calendar Module"><img border="0" src="{$smarty.const.ICON_RELATIVE}configure.gif" /></a>
{/if}
{/permissions}
<table cellspacing="0" cellpadding="0" width="100%" style="border: 1px solid #DDD; border-collapse: collapse" rules="all" class="calendar_monthly">
<tbody>
<tr><td align="left">
<a class="mngmntlink calendar_mngmntlink" href="{link action=viewmonth time=$prevmonth}"><img border="0" src="{$smarty.const.ICON_RELATIVE}left.gif" title="Previous Month" alt="Previous Month" /></a>
</td>
<td align="center" valign="top" colspan="5">{if $moduletitle != ""}{$moduletitle} {/if}{$now|format_date:"%B %Y"}</td>
<td align="right">
<a class="mngmntlink calendar_mngmntlink" href="{link action=viewmonth time=$nextmonth}"><img border="0" src="{$smarty.const.ICON_RELATIVE}right.gif" title="Next Month" alt="Next Month" /></a>
</td></tr>
<tr>
	<td align="center" style="font-weight:bold">Sunday</td>
	<td align="center" style="font-weight:bold">Monday</td>
	<td align="center" style="font-weight:bold">Tuesday</td>
	<td align="center" style="font-weight:bold">Wednesday</td>
	<td align="center" style="font-weight:bold">Thursday</td>
	<td align="center" style="font-weight:bold">Friday</td>
	<td align="center" style="font-weight:bold">Saturday</td>
</tr>
{math equation="x-86400" x=$now assign=dayts}
{foreach from=$monthly item=week key=weeknum}
	<tr class="{if $currentweek == $weeknum}calendar_currentweek{/if}">
		{foreach name=w from=$week key=day item=events}
			{assign var=number value=$counts[$weeknum][$day]}
			<td width="14%" align="left" valign="top" style="height: 100px; {if $number == -1}background-color: #EEE;{/if}">
				{if $number != -1}{math equation="x+86400" x=$dayts assign=dayts}{/if}
				{if $number > -1}
					<div style="border-bottom:1px solid lightgrey; padding: 2px; margin-bottom: .25em; background-color: #DDD">
					{if $number == 0}
						{$day}
					{else}
						<a class="mngmntlink calendar_mngmntlink" href="{link action=viewday time=$dayts}" title="{$dayts|format_date:"%A, %B %e, %Y"}" alt="{$dayts|format_date:"%A, %B %e, %Y"}">{$day}</a>
					{/if}
					</div>
				{/if}
				{foreach name=e from=$events item=event}
					{assign var=catid value=0}
					{if $__viewconfig.colorize == 1 && $modconfig->enable_categories}{assign var=catid value=$event->category_id}{/if}
					<a class="mngmntlink calendar_mngmntlink" href="{link action=view id=$event->id date_id=$event->eventdate->id}"{if $catid != 0} style="color: {$categories[$catid]->color};"{/if}>{$event->title}</a><br />
					{if $smarty.foreach.e.last != 1}<hr size="1" color="lightgrey" />{/if}
				{/foreach}
			</td>
		{/foreach}
	</tr>
{/foreach}
</tbody>
</table>
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.post == 1}
<a class="mngmntlink calendar_mngmntlink" href="{link action=edit id=0}" title="Create a new Calendar Event" alt="Create a new Calendar Event">Create Event</a>
{/if}
{if $in_approval != 0 && $canview_approval_link == 1}
<br />
<a class="mngmntlink calendar_mngmntlink" href="{link module=workflow datatype=calendar m=calendarmodule s=$__loc->src action=summary}" title="View Calendar Events in Approval" alt="View Calendar Events in Approval">View Approval</a>
{/if}
{if $modconfig->enable_categories == 1}
{if $permissions.administrate == 1}
<br />
<a class="mngmntlink calendar_mngmntlink" href="{link action=cat_managecategories}" title="Manage Event Categories" alt="Manage Event Categories">Manage Categories</a>
{else}
<br />
<a class="mngmntlink calendar_mngmntlink" href="#" onClick="window.open('{$smarty.const.PATH_RELATIVE}/popup.php?module={$__loc->mod}&action=cat_viewcategories&src={$__loc->src}','legend','width=200,height=200,title=no,status=no'); return false" title="View Event Categories" alt="View Event Categories">View Categories</a>
{/if}
{/if}
{/permissions}