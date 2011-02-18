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
 
<div class="calendarmodule cal-admin"> 
	<div class="itemactions">
		<a class="monthviewlink" href="{link action=viewmonth time=$time}">{$_TR.calendar_view}</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class="listviewlink" href="{link _common=1 view='Monthly List' action='show_view' time=$time}">{$_TR.list_view}</a>
		{permissions level=$smarty.const.UILEVEL_NORMAL}
			{if $permissions.administrate == 1}
				&nbsp;&nbsp;|&nbsp;&nbsp;<a class="adminviewlink mngmntlink" href="{link _common=1 view='Administration' action='show_view' time=$time}">{$_TR.administration_view}</a>
			{/if}
			&nbsp;&nbsp;|&nbsp;&nbsp;
			{printer_friendly_link class="printer-friendly-link" text=$_TR.printer_friendly}
			{br}
			<span class="listviewlink">{$_TR.past_events}{$config->colorize}</span>
			{if $permissions.administrate == 1}
				&nbsp;&nbsp;|&nbsp;&nbsp;
				<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.delete_past}" alt="{$_TR.delete_past}" />
				<a class="mngmntlink" href="{link action=delete_all_past}" onclick="return confirm('{$_TR.delete_all_confirm}');" title="{$_TR.delete_past}">{$_TR.delete_all_past}</a>
				{br}
			{/if}
			{if $permissions.post == 1}
				{br}
				<a class="addevent mngmntlink" href="{link action=edit id=0}" title="{$_TR.alt_create}" alt="{$_TR.alt_create}">{$_TR.create}</a>
			{/if}
			{if $in_approval != 0 && $canview_approval_link == 1}
				{br}
				<a class="mngmntlink calendar_mngmntlink" href="{link module=workflow datatype=calendar m=calendarmodule s=$__loc->src action=summary}" title="{$_TR.alt_approval}" alt="{$_TR.alt_approval}">{$_TR.view_approval}</a>
			{/if}
			{if $config->enable_categories == 1}
				{br}
				{if $permissions.manage_categories == 1}
					<a href="{link module=categories orig_module=calendarmodule action=manage}" class="mngmntlink cats">{$_TR.manage_categories}</a>
				{else}
					<a class="cats" href="#" onclick="window.open('{$smarty.const.PATH_RELATIVE}popup.php?module=categories&m={$__loc->mod}&action=view&src={$__loc->src}','legend','width=200,height=200,title=no,status=no'); return false" title="{$_TR.alt_view_cat}" alt="{$_TR.alt_view_cat}">{$_TR.view_categories}</a>
				{/if}
			{/if}
		{/permissions}
	</div>
	<h2>
		{if $enable_ical == true}
			<a class="icallink itemactions" href="{link action=ical}" title="{$_TR.alt_ical}" alt="{$_TR.alt_ical}">{$_TR.ical}</a>
		{/if}
		{if $moduletitle != ""}{$moduletitle} - {$_TR.past_events}{/if}
	</h2>
	<table cellspacing="0" cellpadding="4" border="0" width="100%">
		<tr>
			<strong><em>
			<td class="header calendarcontentheader">{$_TR.event_title}</td>
			<td class="header calendarcontentheader">{$_TR.when}</td>
			{if $config->enable_categories == 1}
				<td class="header calendarcontentheader">{$_TR.category}</td>
			{/if}
			<td class="header calendarcontentheader">&nbsp;</td>
			</em></strong>
		</tr>
		{foreach from=$items item=item}
			<tr class="row {cycle values=odd_row,even_row}">
				<td><a class="itemtitle calendar_mngmntlink" href="{link action=view id=$item->id date_id=$item->eventdate->id}" title="{$item->body|summarize:"html":"para"}">{$item->title}</a></td>
				<td>
				{if $item->is_allday == 1}
					{$item->eventstart|format_date:$smarty.const.DISPLAY_DATE_FORMAT}
				{else}
					{if $event->eventstart != $event->eventend}
						{$item->eventstart|format_date:"%b %e %Y"} @ {$item->eventstart|format_date:"%l:%M %p"} - {$event->eventend|format_date:"%l:%M %p"}
					{else}
						{$item->eventstart|format_date:"%b %e %Y"} @ {$item->eventstart|format_date:"%l:%M %p"}
					{/if}		
				{/if}
				</td>
				{if $config->enable_categories == 1}
				<td>{assign var=catid value=$item->category_id}
					<b>
					<span style="color: {$categories[$catid]->color}">{$categories[$catid]->name}</span>
					</b>
				</td>
				{/if}
				<td>
					<div class="itemactions">
						{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
							{if $permissions.administrate == 1 || $item->permissions.administrate == 1}
								<a class="mngmntlink calendar_mngmntlink" href="{link action=userperms int=$item->id _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm_one}" alt="{$_TR.alt_userperm_one}" /></a>
								<a class="mngmntlink calendar_mngmntlink" href="{link action=groupperms int=$item->id _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm_one}" alt="{$_TR.alt_groupperm_one}" /></a>
							{/if}
						{/permissions}
						{permissions level=$smarty.const.UILEVEL_NORMAL}
							{if $permissions.edit == 1 || $item->permissions.edit == 1}
								{if $item->approved == 1}
									<a class="mngmntlink calendar_mngmntlink" href="{link action=edit id=$item->id date_id=$item->eventdate->id}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>
								{else}
									<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.disabled.png" title="{$_TR.alt_edit_disabled}" alt="{$_TR.alt_edit_disabled}" />
								{/if}
							{/if}
							{if $permissions.delete == 1 || $item->permissions.delete == 1}
								{if $item->approved == 1}
									{if $item->is_recurring == 0}
										<a class="mngmntlink calendar_mngmntlink" href="{link action=delete id=$item->id}" onclick="return confirm('{$_TR.delete_confirm}');"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" /></a>
									{else}
										<a class="mngmntlink calendar_mngmntlink" href="{link action=delete_form id=$item->id date_id=$item->eventdate->id}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" /></a>
									{/if}
								{else}
									<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.disabled.png" title="{$_TR.alt_delete_disabled}" alt="{$_TR.alt_delete_disabled}" />
								{/if}
							{/if}
							{if $permissions.manage_approval == 1}
								<a class="mngmntlink calendar_mngmntlink" href="{link module=workflow datatype=calendar m=calendarmodule s=$__loc->src action=revisions_view id=$item->id}" title="{$_TR.alt_revisions}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}revisions.png" title="{$_TR.alt_revisions}" alt="{$_TR.alt_revisions}"/></a>
							{/if}
						{/permissions}
					</div>
				</td>
			</tr>
		{foreachelse}
			<tr><td colspan="2" align="center"><i>{$_TR.no_events}</a></td></tr>
		{/foreach}
	</table>
</div>