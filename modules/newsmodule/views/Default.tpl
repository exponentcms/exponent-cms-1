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

{include file="`$smarty.const.BASE`modules/common/views/_permission_icons.tpl"}
<div class="newsmodule default">
<h1>
	{if $moduletitle != ""}{$moduletitle}{/if}
	{if $enable_rss == true}<a href="{rsslink}"><img src="{$smarty.const.ICON_RELATIVE}rss-feed.gif" title="{$_TR.alt_rssfeed}" alt="{$_TR.alt_rssfeed}" /></a>{/if}
</h1>
{foreach from=$news item=newsitem}
	<div class="item {cycle values='odd,even'}">
		<h3>{$newsitem->title}</h3>
		{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
		{if $permissions.administrate == true || $newsitem->permissions.administrate == true}
			<a href="{link action=userperms int=$newsitem->id _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm_one}" alt="{$_TR.alt_userperm_one}" /></a>&nbsp;
			<a href="{link action=groupperms int=$newsitem->id _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm_one}" alt="{$_TR.alt_groupperm_one}" /></a>
		{/if}
		{/permissions}
		{permissions level=$smarty.const.UILEVEL_NORMAL}
		{if $permissions.edit_item == true || $newsitem->permissions.edit_item == true}
			{if $newsitem->approved == 2} {* in ap *}
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.disabled.png" title="{$_TR.alt_edit_disabled}" alt="{$_TR.alt_edit_disabled}" />
			{else}
			<a class="mngmntlink news_mngmntlink" href="{link action=edit id=$newsitem->id}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>
			{/if}
		{/if}
		{if $permissions.delete_item == true || $newsitem->permissions.delete_item == true}
			{if $newsitem->approved == 2} {* in ap *}
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.disabled.png" title="{$_TR.alt_delete_disabled}" alt="{$_TR.alt_delete_disabled}" />
			{else}
			<a onclick="return confirm('{$_TR.delete_confirm}');" class="mngmntlink news_mngmntlink" href="{link action=delete id=$newsitem->id}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" /></a>
			{/if}
		{/if}
		{if $permissions.manage_approval == 1}
			<a class="mngmntlink news_mngmntlink" href="{link module=workflow datatype=newsitem m=newsmodule s=$__loc->src action=revisions_view id=$newsitem->id}" title="{$_TR.alt_revisions}" alt="{$_TR.alt_revisions}">{$_TR.revisions}</a>
		{/if}
		{/permissions}
		 <div class="text">{$newsitem->body|summarize:"html":"para"}</div>
		<a class="mngmntlink news_mngmntlink" href="{link action=view id=$newsitem->id}">{$_TR.read_more}</a>
	</div>
{/foreach}
{if $morenews == 1}
<a href="{link action=view_all_news}">{$_TR.view_all}</a>
{/if}
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.add_item == true}
	<br /><a class="mngmntlink news_mngmntlink" href="{link action=edit}">{$_TR.create_news}</a>
{/if}
{if $in_approval > 0 && $canview_approval_link == 1}
	<br /><a class="mngmntlink news_mngmntlink" href="{link module=workflow datatype=newsitem m=newsmodule s=$__loc->src action=summary}">{$_TR.view_approval}</a>
{/if}
{if $permissions.view_unpublished == 1}
	<br /><a class="mngmntlink news_mngmntlink" href="{link action=view_expired}">{$_TR.view_expired}</a>
{/if}
{/permissions}
