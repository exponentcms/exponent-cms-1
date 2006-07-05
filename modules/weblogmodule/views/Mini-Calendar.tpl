{*
 * Copyright (c) 2004-2006 OIC Group, Inc.
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
	<a href="{link action=userperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
{/if}
{if $permissions.configure == 1}
	<a href="{link action=configure _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}configure.png" title="{$_TR.alt_configure}" alt="{$_TR.alt_configure}" /></a>
{/if}
{/permissions}
<table cellspacing="0" cellpadding="2" border="0" width="160">
<tr><td align="center" class="weblog_header" colspan="7">{if $moduletitle != ""}{$moduletitle} {/if}{$now|format_date:"%B"}</td></tr>
	<tr>
		<td align="center" class="weblog_miniday">{$_TR.sunday_short}</td>
		<td align="center" class="weblog_miniday">{$_TR.monday_short}</td>
		<td align="center" class="weblog_miniday">{$_TR.tuesday_short}</td>
		<td align="center" class="weblog_miniday">{$_TR.wednesday_short}</td>
		<td align="center" class="weblog_miniday">{$_TR.thursday_short}</td>
		<td align="center" class="weblog_miniday">{$_TR.friday_short}</td>
		<td align="center" class="weblog_miniday">{$_TR.saturday_short}</td>
	</tr>
{foreach from=$days item=week key=weekid}
	<tr class="{if $currentweek == $weekid}weblog_currentweek{/if}">
		{foreach from=$week key=day item=dayinfo}
			<td align="center">
			{if $day > 0}
				{if $dayinfo.number == 0}
					{$day}
				{else}
					<a class="mngmntlink weblog_mngmntlink" href="{link action=view_day day=$dayinfo.ts}" title="{$dayinfo.ts|format_date:"%A, %B %e, %Y"}" alt="{$dayinfo.ts|format_date:"%A, %B %e, %Y"}"><b>{$day}</b></a>
				{/if}
			{else}
				&nbsp;
			{/if}
			</td>
		{/foreach}
	</tr>
{/foreach}
</table>
<br />
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.post == 1}
<a class="mngmntlink weblog_mngmntlink" href="{link action=edit}" title="{$_TR.alt_create}" alt="{$_TR.alt_create}">{$_TR.create}</a><br />
{/if}
{if $in_approval != 0 && $canview_approval_link == 1}
<a class="mngmntlink weblog_mngmntlink" href="{link module=workflow datatype=calendar m=calendarmodule s=$__loc->src action=summary}" title="{$_TR.alt_approval}" alt="{$_TR.alt_approval}">{$_TR.view_approval}</a><br />
{/if}
{/permissions}
<br />

{if $modconfig->enable_categories == 1}
<a href="{link module=categories m=calendarmodule action=manage}" class="mngmntlink weblog_mngmntlink">{$_TR.manage_categories}</a>
{/if}