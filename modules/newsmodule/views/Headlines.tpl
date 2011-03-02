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
 
<div class="newsmodule headlines">
	<h2>
	{if $enable_rss == true}
		<a href="{rsslink}"><img src="{$smarty.const.ICON_RELATIVE}rss-feed.gif" title="{$_TR.alt_rssfeed}" alt="{$_TR.alt_rssfeed}" /></a>
	{/if}
	{if $moduletitle != ""}{$moduletitle}{/if}
	</h2>
	{permissions level=$smarty.const.UILEVEL_NORMAL}
		{if $permissions.add_item == true}
			<a class="addnews mngmntlink" href="{link action=edit}">{$_TR.create_news}</a>{br}
		{/if}
		{if $in_approval > 0 && $canview_approval_link == 1}
			<a class="approvenews mngmntlink" href="{link module=workflow datatype=newsitem m=newsmodule s=$__loc->src action=summary}">{$_TR.view_approval}</a>{br}
		{/if}
		{if $permissions.view_unpublished == 1}
			<a class="expirednews mngmntlink" href="{link action=view_expired}">{$_TR.view_expired}</a>
		{/if}
	{/permissions}
</div>
<div class="linklistmodule quick-links">
	<ul>
	{assign var=more_news value=0}	
	{assign var=item_number value=0}	
	{foreach from=$news item=newsitem}
{if (!$__viewconfig.num_items || $item_number < $__viewconfig.num_items) }	
		<li>
		<div>
			<div class="itemtitle news_itemtitle">
				<a href="{link action=view id=$newsitem->id}" title="{$newsitem->body|summarize:"html":"para"}">{$newsitem->title}</a>
			</div>
			<div class="itemactions">
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
						<a class="mngmntlink news_mngmntlink" href="{link module=workflow datatype=newsitem m=newsmodule s=$__loc->src action=revisions_view id=$newsitem->id}" title="{$_TR.alt_revisions}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}revisions.png" title="{$_TR.alt_revisions}" alt="{$_TR.alt_revisions}"/></a>
					{/if}
				{/permissions}
			</div>
			{if $newsitem->edited == 0 || $config->sortfield == "publish"}
				{*assign var='sortdate' value=$newsitem->real_posted*}
				{assign var='sortdate' value=$newsitem->posted}
			{else}
				{assign var='sortdate' value=$newsitem->edited}
			{/if}		
			{$sortdate|format_date:$smarty.const.DISPLAY_DATE_FORMAT}
		</div>
		</li>
		{assign var=item_number value=$item_number+1}
{else}
	{assign var=more_news value=1}
{/if}
	{foreachelse}
		<li align="center"><i>{$_TR.no_news}No recent news</i></li>	
	{/foreach}
	</ul>
</div>
<div class="newsmodule headlines">
	<div class="moduleactions">
		<p>
			{if $morenews == 1 || $more_news == 1}
				<a class="viewmorenews" href="{link action=view_page page=0}">{$_TR.view_all}</a>{br}
			{/if}
		</p>
	</div>
</div>