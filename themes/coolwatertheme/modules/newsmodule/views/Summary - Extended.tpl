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

<div class="newsmodule summary-extended">
	
 {permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
{/if}
{if $permissions.configure == 1}
	<a href="{link action=configure _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}configure.png" title="{$_TR.alt_configure}" alt="{$_TR.alt_configure}" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}

{selectObjects table="newsitem" where="is_featured=1 and location_data='`$news[0]->location_data`'" orderby="posted limit 0,1" item=nofeatured}

{if $moduletitle != ""}
<h1>
{if $enable_rss == true && $nofeatured[0]->id==""}<a class="rss" href="{rsslink}"><img src="{$smarty.const.THEME_RELATIVE}images/rsshome.gif" title="{$_TR.alt_rssfeed}" alt="{$_TR.alt_rssfeed}" /></a>{/if}
{$moduletitle}
</h1>
{/if}
{assign var='newscount' value=0}
{foreach from=$news item=item}
{if $item->is_featured==0 && $newscount < 3}
	{counter assign='newscount'}
	{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
		{if $permissions.administrate == 1 || $item->permissions.administrate == 1}
			<a class="mngmntlink news_mngmntlink" href="{link action=userperms int=$item->id _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm_one}" alt="{$_TR.alt_userperm_one}" /></a>
			<a class="mngmntlink news_mngmntlink" href="{link action=groupperms int=$item->id _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm_one}" alt="{$_TR.alt_groupperm_one}" /></a>
		{/if}
	{/permissions}
	{permissions level=$smarty.const.UILEVEL_NORMAL}
		{if $permissions.edit_item == 1 || $item->permissions.edit_item == 1}
			{if $item->approved == 1}
			<a class="mngmntlink news_mngmntlink" href="{link action=edit id=$item->id date_id=$item->eventdate->id}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>
			{else}
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.disabled.png" title="{$_TR.alt_edit_disabled}" alt="{$_TR.alt_edit_disabled}" />
			{/if}
		{/if}
		{if $permissions.delete_item == 1 || $item->permissions.delete_item == 1}
			{if $item->approved == 1}
			{if $item->is_recurring == 0}
			<a class="mngmntlink news_mngmntlink" href="{link action=delete id=$item->id}" onclick="return confirm('{$_TR.delete_confirm}');"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" /></a>
			{else}
			<a class="mngmntlink news_mngmntlink" href="{link action=delete_form id=$item->id date_id=$item->eventdate->id}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" /></a>
			{/if}
			{else}
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.disabled.png" title="{$_TR.alt_delete_disabled}" alt="{$_TR.alt_delete_disabled}" />
			{/if}
		{/if}
		{if $permissions.manage_approval == 1}
			<a class="mngmntlink news_mngmntlink" href="{link module=workflow datatype=calendar m=calendarmodule s=$__loc->src action=revisions_view id=$item->id}" title="{$_TR.alt_revisions}" alt="{$_TR.alt_revisions}">
				<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}revisions.png" title="{$_TR.alt_revisions}" alt="{$_TR.alt_revisions}" /> 
			</a>
		{/if}
	{/permissions}<div class="item" onclick="window.location='{link action=view id=$item->id date_id=$item->eventdate->id}'">
	{*if $item->image_path}
		<img src="{$item->image_path}" border="0" width="80" height="80"/>
	{/if*}
	<div class="attributions">
		<h2><a href="{link action=view id=$item->id date_id=$item->eventdate->id}">{$item->title}</a></h2>
		{if $item->edited eq 0}
			{assign var='sortdate' value=$item->real_posted}
		{else}
			{assign var='sortdate' value=$item->edited}
		{/if}	
		<span class="date">{$sortdate|format_date:"%B %e"}</span>
	</div>
	<div class="text">
		{$item->body|summarize:html:para}
	</div>
</div>

{/if}
{/foreach}
<div class="moduleactions">
	{*if $morenews == 1*}
	<a class="viewallhome" href="{link action=view_all_news}">View all News</a>
	{*/if*}
	{permissions level=$smarty.const.UILEVEL_NORMAL}
	{if $permissions.add_item == true}
		<a class="addnews" href="{link action=edit}">{$_TR.create_news}</a>
	{/if}
	{if $in_approval > 0 && $canview_approval_link == 1}
		<a class="mngmntlink news_mngmntlink" href="{link module=workflow datatype=newsitem m=newsmodule s=$__loc->src action=summary}">{$_TR.view_approval}</a>
	{/if}
	{if $permissions.view_unpublished == 1}
		<a class="expired" href="{link action=view_expired}">{$_TR.view_expired}</a>
	{/if}
	{/permissions}
</div>


</div>