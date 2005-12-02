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
{if $permissions.administrate == 1 || $item->permissions.administrate == 1}
	<a href="{link action=userperms int=$item->id _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>&nbsp;
	<a href="{link action=groupperms int=$item->id _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
{/if}
{if $permissions.edit == 1 || $item->permissions.edit == 1}
	{if $item->approved == 1}
	<a href="{link action=edit id=$item->id date_id=$item->eventdate->id}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>&nbsp;
	{else}
	<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}edit.disabled.png" title="{$_TR.alt_edit_disabled}" alt="{$_TR.alt_edit_disabled}" />
	{/if}
{/if}
{if $permissions.delete == 1 || $item->permissions.delete == 1}
	{if $item->approved == 1}
		{if $item->is_recurring == 0}
		<a href="{link action=delete id=$item->id}" onClick="return confirm('{$_TR.delete_confirm}');"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" /></a>
		{else}
		<a href="{link action=delete_form date_id=$item->eventdate->id id=$item->id}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" /></a>
		{/if}
	{else}
	<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.disabled.png" title="{$_TR.alt_delete_disabled}" alt="{$_TR.alt_delete_disabled}" />
	{/if}
{/if}
{if $permissions.manage_approval == 1}
	<a class="mngmntlink calendar_mngmntlink" href="{link module=workflow datatype=calendar m=calendarmodule s=$__loc->src action=revisions_view id=$item->id}" title="{$_TR.alt_revisions}" alt="{$_TR.alt_revisions}">{$_TR.alt_revisions}</a>
{/if}
{if $permissions.administrate == 1 || $item->permissions.administrate == 1 || $permissions.delete == 1 || $item->permissions.delete == 1 || $permissions.edit == 1 || $item->permissions.edit == 1}
<br />
{/if}
<h3>{$item->title}</h3>
{if $item->is_allday == 1}
{$item->eventstart|format_date:$smarty.const.DISPLAY_DATE_FORMAT}, All Day
{else}
{$item->eventstart|format_date:"%B %e, %Y, %l:%M %P"} - {$item->eventend|format_date:"%l:%M %P"}
{/if}
<hr size="1" />
{$item->body}
{if $item->body}
<br />
<hr size="1" />
{/if}
{$form}
<a class="mngmntlink calendar_mngmntlink" href="{link action=viewweek time=$item->eventstart}" title="{$_TR.alt_view_week}" alt="{$_TR.alt_view_week}">{$_TR.view_week}</a>&nbsp;|&nbsp;
<a class="mngmntlink calendar_mngmntlink" href="{link action=viewmonth time=$item->eventstart}" title="{$_TR.alt_view_month}" alt="{$_TR.alt_view_month}">{$_TR.view_month}</a><br />