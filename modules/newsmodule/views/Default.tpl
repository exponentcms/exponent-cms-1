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

<div class="newsmodule default">
	<div class="permissions">
		{include file="`$smarty.const.BASE`modules/common/views/_permission_icons.tpl"}
	</div>
	{if $moduletitle != ""}
		<h1>
			{if $enable_rss == true}
	        		<a class="rsslink" href="{rsslink}"><img src="{$smarty.const.ICON_RELATIVE}rss-feed.gif" title="{$_TR.alt_rssfeed}" alt="{$_TR.alt_rssfeed}" /></a>
			{/if}
			{$moduletitle}
		</h1>
	{else}
		{if $enable_rss == true}
			<h1>
	        		<a class="rsslink" href="{rsslink}"><img src="{$smarty.const.ICON_RELATIVE}rss-feed.gif" title="{$_TR.alt_rssfeed}" alt="{$_TR.alt_rssfeed}" /></a>
	        	</h1>
		{/if}
	
	{/if}	
	{foreach from=$news item=newsitem}
	{if $newsitem->is_featured!=1}
		<div class="item {cycle values='odd,even'}">
		{if $newsitem->title != ""}<h2>{$newsitem->title}</h2>{/if}
            {if $newsitem->isRss != true}
			{permissions level=$smarty.const.UILEVEL_NORMAL}
			<div class="itemactions">
				{if $permissions.administrate == true || $newsitem->permissions.administrate == true}
					<a href="{link action=userperms int=$newsitem->id _common=1}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm_one}" alt="{$_TR.alt_userperm_one}" /></a>&nbsp;
					<a href="{link action=groupperms int=$newsitem->id _common=1}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm_one}" alt="{$_TR.alt_groupperm_one}" /></a>
				{/if}
				{if $permissions.edit_item == true || $newsitem->permissions.edit_item == true}
					{if $newsitem->approved == 2} {* in ap *}
					<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.disabled.png" title="{$_TR.alt_edit_disabled}" alt="{$_TR.alt_edit_disabled}" />
					{else}
					<a class="mngmntlink news_mngmntlink" href="{link action=edit id=$newsitem->id}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>
					{/if}
				{/if}
				{if $permissions.delete_item == true || $newsitem->permissions.delete_item == true}
					{if $newsitem->approved == 2} {* in ap *}
					<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.disabled.png" title="{$_TR.alt_delete_disabled}" alt="{$_TR.alt_delete_disabled}" />
					{else}
					<a onclick="return confirm('{$_TR.delete_confirm}');" class="mngmntlink news_mngmntlink" href="{link action=delete id=$newsitem->id}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" /></a>
					{/if}
				{/if}
				{if $permissions.manage_approval == 1}
				<a class="mngmntlink news_mngmntlink" href="{link module=workflow datatype=newsitem m=newsmodule s=$__loc->src action=revisions_view id=$newsitem->id}" title="{$_TR.alt_revisions}" alt="{$_TR.alt_revisions}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}revisions.png" title="{$_TR.alt_revisions}" alt="{$_TR.alt_revisions}"/></a>
				{/if}
			</div>
			{/permissions}
            {/if}
			{if $newsitem->image!=""}<img src="{$smarty.const.URL_FULL}/thumb.php?file={$newsitem->image}&constraint=1&width=150&height=200" alt="{$newsitem->title}">{/if}
			{if $newsitem->edited eq 0}
							{assign var='sortdate' value=$newsitem->real_posted}
					{else}
							{assign var='sortdate' value=$newsitem->edited}
					{/if}

			<span class="date">{$sortdate|format_date:"%B %e"}</span>

			<div class="bodycopy">
				{$newsitem->body|summarize:"html":"para"}
				<a class="readmore" href="{if $newsitem->isRss}{$newsitem->rss_link}{else}{link action=view id=$newsitem->id}{/if}">Read More</a>
			</div>
		</div>
	{/if}
	{/foreach}
	
	{permissions level=$smarty.const.UILEVEL_NORMAL}
	{if $morenews == 1 || $permissions.add_item == true || ($in_approval > 0 && $canview_approval_link == 1) || $permissions.view_unpublished == 1}
	<div class="moduleactions">
		<ul>
		{if $morenews == 1}
			<li><a class="viewmorenews" href="{link action=view_all_news}">{$_TR.view_all}</a></li>
		{/if}
		{permissions level=$smarty.const.UILEVEL_NORMAL}
		{if $permissions.add_item == true}
			<li><a class="addnews" href="{link action=edit}">{$_TR.create_news}</a></li>
		{/if}
		{if $in_approval > 0 && $canview_approval_link == 1}
			<li><a class="approvenews" href="{link module=workflow datatype=newsitem m=newsmodule s=$__loc->src action=summary}">{$_TR.view_approval}</a></li>
		{/if}
		{if $permissions.view_unpublished == 1 }
			<li><a class="expirednews" href="{link action=view_expired}">{$_TR.view_expired}</a></li>
		{/if}
		{/permissions}
	</div>
	{/if}
	{/permissions}
</div>
