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
<table cellspacing="0" cellpadding="2" width="100%" style="border: 1px solid #DDD; border-collapse: collapse" rules="all">
<tbody>
<tr><td align="left">
<a class="mngmntlink calendar_mngmntlink" href="{link action=viewmonth time=$prevmonth}"><img border="0" src="{$smarty.const.ICON_RELATIVE}left.gif"/></a>
</td>
<td align="center" valign="top" colspan="5">{$now|format_date:"%B"}</td>
<td align="right">
<a class="mngmntlink calendar_mngmntlink" href="{link action=viewmonth time=$nextmonth}"><img border="0" src="{$smarty.const.ICON_RELATIVE}right.gif"/></a>
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
				{if $smarty.foreach.w.first == 1}
					<table cellspacing="0" cellpadding="0" width="100%"><tr><td>
					{if $number > -1}
						{if $number == 0}
							{$day}
						{else}
							<a class="mngmntlink calendar_mngmntlink" href="{link action=viewday time=$dayts}">{$day}</a>
						{/if}
					{else}
					{/if}
					</td><td align="right">
					<a class="mngmntlink calendar_mngmntlink" href="{link action=viewweek time=$dayts}">Week</a>
					</td></tr></table>
				{else}
					{if $number > -1}
						{if $number == 0}
							{$day}
						{else}
							<a class="mngmntlink calendar_mngmntlink" href="{link action=viewday time=$dayts}">{$day}</a>
						{/if}
					{else}
					{/if}
				{/if}
				<br />
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