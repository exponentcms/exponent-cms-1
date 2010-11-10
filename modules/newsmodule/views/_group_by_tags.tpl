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
 
<div class="newsmodule groupbytags"> 
	<h2>
	{if $enable_rss == true}
			<a href="{rsslink}"><img src="{$smarty.const.ICON_RELATIVE}rss-feed.gif" title="{$_TR.alt_rssfeed}" alt="{$_TR.alt_rssfeed}" /></a>
	{/if}
	{if $moduletitle != ""}{$moduletitle}{/if}
	</h2>
	{foreach from=$news item=tag key=tag_name}
		<div class="itemtitle news_itemtitle">{$_TR.news_about} {$tag_name}</div>
		{foreach from=$tag item=newsitem}
			{if $item->edited eq 0 || $config->sortfield == "publish" || $config->sortfield == "posted"}
				{*assign var='sortdate' value=$item->real_posted*}
				{assign var='sortdate' value=$item->posted}
			{else}
				{assign var='sortdate' value=$item->edited}
			{/if}	
			<ul>
				<!--div style="margin-top:5px;margin-bottom:5px;margin-left:15px;"-->
				<div>
					<li class="news_itemtitle">
						<div class="itemtitle news_itemtitle">
							<a class="mngmntlink news_mngmntlink" href="{link action=view id=$newsitem->id}">{$newsitem->title}</a>
						</div>
						{$_TR.posted}{$sortdate|format_date:"%B %d, %Y"}<br />[<a href="{link action=view id=$newsitem->id}">{$_TR.read_more}<span> "{$newsitem->title}"</span></a>]
					</li>
					{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
						{if $permissions.administrate == true || $newsitem->permissions.administrate == true}
							<a href="{link action=userperms int=$newsitem->id _common=1}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm_one}" alt="{$_TR.alt_userperm_one}" /></a>&nbsp;
							<a href="{link action=groupperms int=$newsitem->id _common=1}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm_one}" alt="{$_TR.alt_groupperm_one}" /></a>
						{/if}
					{/permissions}
					{permissions level=$smarty.const.UILEVEL_NORMAL}
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
							<a class="mngmntlink news_mngmntlink" href="{link module=workflow datatype=newsitem m=newsmodule s=$__loc->src action=revisions_view id=$newsitem->id}" title="{$_TR.alt_revisions}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}revisions.png" title="{$_TR.alt_revisions}" alt="{$_TR.alt_revisions}"/></a>
							{/if}
					{/permissions}
				</div>
			</ul>
		{/foreach}
	{/foreach}
	{if $morenews == 1}
		<p><a class="viewmorenews mngmntlink" href="{link action=view_page page=1}">{$_TR.view_all}</a></p>
	{else}
		{br}
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
</div>
