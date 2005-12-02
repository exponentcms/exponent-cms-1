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
{$_TR.calendar_view}&nbsp;&nbsp;|&nbsp;&nbsp;<a href="{link _common=1 view='Monthly List' action='show_view' time=$time}">{$_TR.list_view}</a><br /><br />
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" /></a>
{/if}
{if $permissions.configure == 1}
	<a href="{link action=configure _common=1}" title="{$_TR.alt_configure}" alt="{$_TR.alt_configure}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}configure.png" /></a>
{/if}
{/permissions}
<table cellspacing="0" cellpadding="0" width="100%" style="border: 1px solid #DDD; border-collapse: collapse" rules="all" class="calendar_monthly">
<tbody>
<tr><td align="left">
<a class="mngmntlink calendar_mngmntlink" href="{link action=viewmonth time=$prevmonth}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}left.png" title="{$_TR.alt_previous}" alt="{$_TR.alt_previous}" /></a>
</td>
<td align="center" valign="top" colspan="5">{if $moduletitle != ""}{$moduletitle} {/if}{$now|format_date:"%B %Y"}</td>
<td align="right">
<a class="mngmntlink calendar_mngmntlink" href="{link action=viewmonth time=$nextmonth}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}right.png" title="{$_TR.alt_next}" alt="{$_TR.alt_next}" /></a>
</td></tr>
<tr>
	<td align="center" style="font-weight:bold">{$_TR.sunday}</td>
	<td align="center" style="font-weight:bold">{$_TR.monday}</td>
	<td align="center" style="font-weight:bold">{$_TR.tuesday}</td>
	<td align="center" style="font-weight:bold">{$_TR.wednesday}</td>
	<td align="center" style="font-weight:bold">{$_TR.thursday}</td>
	<td align="center" style="font-weight:bold">{$_TR.friday}</td>
	<td align="center" style="font-weight:bold">{$_TR.saturday}</td>
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
						<a class="mngmntlink calendar_mngmntlink" href="{link action=viewday time=$dayts}" title="{$dayts|format_date:'%A, %B %e, %Y'}" alt="{$dayts|format_date:'%A, %B %e, %Y'}">{$day}</a>
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
<a class="mngmntlink calendar_mngmntlink" href="{link action=edit id=0}" title="{$_TR.alt_create}" alt="{$_TR.alt_create}">{$_TR.create}</a>
{/if}
{if $in_approval != 0 && $canview_approval_link == 1}
<br />
<a class="mngmntlink calendar_mngmntlink" href="{link module=workflow datatype=calendar m=calendarmodule s=$__loc->src action=summary}" title="{$_TR.alt_approval}" alt="{$_TR.alt_approval}">{$_TR.view_approval}</a>
{/if}
{if $modconfig->enable_categories == 1}
{if $permissions.manage_categories == 1}
<br />
<a href="{link module=categories orig_module=calendarmodule action=manage}" class="mngmntlink calendar_mngmntlink">{$_TR.manage_categories}</a>
{else}
<br />
<a class="mngmntlink calendar_mngmntlink" href="#" onClick="window.open('{$smarty.const.PATH_RELATIVE}/popup.php?module=categories&m={$__loc->mod}&action=view&src={$__loc->src}','legend','width=200,height=200,title=no,status=no'); return false" title="{$_TR.alt_view_cat}" alt="{$_TR.alt_view_cat}">{$_TR.view_categories}</a>
{/if}
{/if}
{/permissions}