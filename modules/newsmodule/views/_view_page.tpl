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
	<h2>
	{if $enable_rss == true}
			<a href="{rsslink}"><img src="{$smarty.const.ICON_RELATIVE}rss-feed.gif" title="{$_TR.alt_rssfeed}" alt="{$_TR.alt_rssfeed}" /></a>
	{/if}
	{if $moduletitle != ""}{$moduletitle}{/if}
	</h2>

	{foreach from=$news item=newsitem}
		{*if $newsitem->is_featured!=1*}
			<div class="item {cycle values='odd,even'}">
			{if $newsitem->title != ""}<h3><a href="{link action=view id=$newsitem->id}">{$newsitem->title}</a></h3>{/if}
			{if $newsitem->isRss != true}
				{permissions level=$smarty.const.UILEVEL_NORMAL}
					<div class="itemactions">
						{if $permissions.administrate == true || $newsitem->permissions.administrate == true}
							<a href="{link action=userperms int=$newsitem->id _common=1}"><img src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm_one}" alt="{$_TR.alt_userperm_one}" /></a>&nbsp;
							<a href="{link action=groupperms int=$newsitem->id _common=1}"><img src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm_one}" alt="{$_TR.alt_groupperm_one}" /></a>
						{/if}
						{if $permissions.edit_item == true || $newsitem->permissions.edit_item == true}
							{if $newsitem->approved == 2} {* in approval *}
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
					</div>
				{/permissions}
				{*/if*}
				<div class="bodycopy">
					{if $newsitem->image!=""}<img src="{$smarty.const.URL_FULL}/thumb.php?file={$newsitem->image}&constraint=1&width=150&height=200" alt="{$newsitem->title}">{/if}				
					{$newsitem->body|summarize:"html":"paralinks"}
				</div>
				<div class="post-footer">
					{if $newsitem->edited == 0 || $config->sortfield == "publish" || $config->sortfield == "posted"}
						{*assign var='sortdate' value=$newsitem->real_posted*}
						{assign var='sortdate' value=$newsitem->posted}
						{assign var='typepost' value=$_TR.posted}
						{assign var='whopost'  value=$newsitem->poster}
					{else}
						{assign var='sortdate' value=$newsitem->edited}
						{assign var='typepost' value=$_TR.updated}
						{assign var='whopost'  value=$newsitem->editor}
					{/if}					
					<a class="readmore" href="{if $newsitem->isRss}{$newsitem->rss_link}{else}{link action=view id=$newsitem->id}{/if}">{$_TR.read_more}<span> {$_TR.on} "{$newsitem->title}"</span></a>
					| Read {$newsitem->reads} times |
					{if $newsitem->posted > $smarty.now}
						<b><u>{$_TR.will_be}&nbsp;
					{elseif ($newsitem->unpublish != 0) && $newsitem->unpublish <= $smarty.now}
						<b><u>{$_TR.was}&nbsp;
					{/if}
					{$typepost}{if $config->show_poster} {$_TR.by} {attribution user_id=$whopost} {$_TR.on} {/if}&nbsp;{$sortdate|format_date:$smarty.const.DISPLAY_DATE_FORMAT}
					{if $newsitem->posted > $smarty.now}
						</u></b>&nbsp;
					{elseif ($newsitem->unpublish != 0) && $newsitem->unpublish <= $smarty.now}
						{$_TR.now_unpublished}</u></b>&nbsp;
					{/if}
				</div>				
			</div>
		{/if}
	{/foreach}

	{permissions level=$smarty.const.UILEVEL_NORMAL}
		<div class="moduleactions">
			<p>
				{if $enable_pagination == 1}
					{if $page != 0}
						{math equation="x-1" x=$page assign=prevpage}
						<a class="news_mngmntlink" href="{link action=view_page page=$prevpage}">{$_TR.prev}</a>
					{else}
						{$_TR.prev}
					{/if}
					&nbsp;&nbsp;|&nbsp;&nbsp;
					{if $shownext}
						{math equation="x+1" x=$page assign=nextpage}
						<a class="news_mngmntlink" href="{link action=view_page page=$nextpage}">{$_TR.next}</a>
					{else}
						{$_TR.next}
					{/if}
					{br}
				{/if}
				{permissions level=$smarty.const.UILEVEL_NORMAL}
					{if $permissions.add_item == true}
						<a class="addnews mngmntlink" href="{link action=edit}">{$_TR.create_news}</a>{br}
					{/if}
					{if $in_approval > 0 && $canview_approval_link == 1}
						<a class="approvenews mngmntlink" href="{link module=workflow datatype=newsitem m=newsmodule s=$__loc->src action=summary}">{$_TR.view_approval}</a>{br}
					{/if}
					{if $permissions.view_unpublished == 1 }
						<a class="expirednews mngmntlink" href="{link action=view_expired}">{$_TR.view_expired}</a>
					{/if}
				{/permissions}
			</p>
		</div>
	{/permissions}
</div>
