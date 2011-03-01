{*
 * Copyright (c) 2004-2011 OIC Group, Inc.
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
 
<div class="calendarmodule viewtag">
	<div class="itemactions">
		<a class="monthviewlink" href="{link action=viewmonth time=$time}">{$_TR.calendar_view}</a>
		{permissions level=$smarty.const.UILEVEL_NORMAL}
			{if $permissions.administrate == 1}
				&nbsp;&nbsp;|&nbsp;&nbsp;<a class="adminviewlink mngmntlink" href="{link _common=1 view='Administration' action='show_view' time=$time}">{$_TR.administration_view}</a>
			{/if}
		{/permissions}
		&nbsp;&nbsp;|&nbsp;&nbsp;{printer_friendly_link class="printer-friendly-link" text=$_TR.printer_friendly}
	</div>
	<h2>
		{if $enable_ical == true}
			<a class="icallink itemactions" href="{link action=ical}" title="{$_TR.alt_ical}" alt="{$_TR.alt_ical}">{$_TR.ical}</a>
		{/if}
		{if $moduletitle != ""}{$moduletitle}{/if}
	</h2>
	<div class="itemactions">
	{permissions level=$smarty.const.UILEVEL_NORMAL}
		{if $permissions.post == 1}
			{br}
			<a class="addevent mngmntlink" href="{link action=edit id=0}" title="{$_TR.alt_create}" alt="{$_TR.alt_create}">{$_TR.create}</a>
		{/if}
		{if $in_approval != 0 && $canview_approval_link == 1}
			{br}
			<a class="mngmntlink calendar_mngmntlink" href="{link module=workflow datatype=calendar m=calendarmodule s=$__loc->src action=summary}" title="{$_TR.alt_approval}" alt="{$_TR.alt_approval}">{$_TR.approval}</a>
			{br}
		{/if}
		{if $config->enable_categories == 1}
			{br}
			{if $permissions.manage_categories == 1}
				<a class="mngmntlink mngmntlinkcats cats" href="{link module=categories orig_module=calendarmodule action=manage}" class="mngmntlink calendar_mngmntlink">{$_TR.manage_categories}</a>
			{else}
				<a class="cats" href="#" onclick="window.open('{$smarty.const.PATH_RELATIVE}popup.php?module=categories&m={$__loc->mod}&action=view&src={$__loc->src}','legend','width=200,height=200,title=no,status=no'); return false" title="{$_TR.alt_view_cat}" alt="{$_TR.alt_view_cat}">{$_TR.view_categories}</a>
			{/if}
		{/if}
	{/permissions}
	</div>
	<dl class="viewweek">
	{foreach from=$items item=item}
		<dt>
			<b><a class="itemtitle" href="{link action=view id=$item->id date_id=$item->eventdate->id}">{$item->title}</a></b>
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
					<a class="mngmntlink calendar_mngmntlink" href="{link module=workflow datatype=calendar m=calendarmodule s=$__loc->src action=revisions_view id=$item->id}" title="{$_TR.alt_revisions}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}revisions.png" title="{$_TR.alt_revisions}" alt="{$_TR.alt_revisions}" /></a>
				{/if}
			{/permissions}
			</div>
		</dt>
		<dd>
			<strong>
			{if $item->is_allday == 1}
				{$item->eventstart|format_date:$smarty.const.DISPLAY_DATE_FORMAT}
			{elseif $item->eventstart != $item->eventend}
				{$item->eventstart|format_date:$smarty.const.DISPLAY_DATE_FORMAT} @ {$item->eventstart|format_date:$smarty.const.DISPLAY_TIME_FORMAT} to {$item->eventend|format_date:$smarty.const.DISPLAY_TIME_FORMAT}
			{else}
				{$item->eventstart|format_date:$smarty.const.DISPLAY_DATE_FORMAT} @ {$item->eventstart|format_date:$smarty.const.DISPLAY_TIME_FORMAT}
			{/if}
			</strong>
		</dd>
		<dd>
			{$item->body|summarize:html:paralinks}
		</dd>
	{foreachelse}
		<<dd><em>{$_TR.no_events}</em></dd>
	{/foreach}
	</dl>
</div>