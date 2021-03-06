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
<div class="newsmodule summary">

	{include file="`$smarty.const.BASE`modules/common/views/_permission_icons.tpl"}

	{if $enable_rss == true}<a class="rsslink" href="{rsslink}">{$_TR.alt_rssfeed}</a>{/if}
	{if $moduletitle != ""}<h1>{$moduletitle}</h1>{/if}
	{foreach key="key" name="object" from=$news item=newsitem}
	<div class="item {cycle values='odd,even'}">
		{if $newsitem->isRss != true}
			{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
				<div class="itemactions">
					{if $permissions.administrate == true || $newsitem->permissions.administrate == true}
						<a href="{link action=userperms int=$newsitem->id _common=1}"><img src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm_one}" alt="{$_TR.alt_userperm_one}" {$smarty.const.XHTML_CLOSING}></a>&nbsp;
						<a href="{link action=groupperms int=$newsitem->id _common=1}"><img src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm_one}" alt="{$_TR.alt_groupperm_one}" {$smarty.const.XHTML_CLOSING}></a>
					{/if}
					{if $permissions.edit_item == true || $newsitem->permissions.edit_item == true}
						{if $newsitem->approved == 2} {* in ap *}
							<img src="{$smarty.const.ICON_RELATIVE}edit.disabled.png" title="{$_TR.alt_edit_disabled}" alt="{$_TR.alt_edit_disabled}" {$smarty.const.XHTML_CLOSING}>
						{else}
							<a href="{link action=edit id=$newsitem->id}"><img src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" {$smarty.const.XHTML_CLOSING}></a>
						{/if}
					{/if}
					{if $permissions.delete_item == true || $newsitem->permissions.delete_item == true}
						{if $newsitem->approved == 2} {* in ap *}
							<img src="{$smarty.const.ICON_RELATIVE}delete.disabled.png" title="{$_TR.alt_delete_disabled}" alt="{$_TR.alt_delete_disabled}" {$smarty.const.XHTML_CLOSING}>
						{else}
							<a onclick="return confirm('{$_TR.delete_confirm}');" href="{link action=delete id=$newsitem->id}"><img src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" {$smarty.const.XHTML_CLOSING}></a>
						{/if}
					{/if}
					{if $permissions.manage_approval == 1}
						<a class="managerevisions" href="{link module=workflow datatype=newsitem m=newsmodule s=$__loc->src action=revisions_view id=$newsitem->id}" title="{$_TR.alt_revisions}">
							<img src="{$smarty.const.ICON_RELATIVE}revisions.png" alt="{$_TR.revisions}" {$smarty.const.XHTML_CLOSING}>
						</a>
					{/if}
				</div>
			{/permissions}
		{/if}
		{if $newsitem->title != ""}<h2>{$newsitem->title}</h2>{/if}
		{if $newsitem->edited == 0 || $config->sortfield == "publish"}
			{assign var='sortdate' value=$newsitem->real_posted}
		{else}
			{assign var='sortdate' value=$newsitem->edited}
		{/if}

		<span class="date">{$sortdate|format_date:"%A, %B %e, %Y"}</span>

		<div class="bodycopy">
			<p>{$newsitem->body|summarize:"html":"para"}</p>
			<a class="readmore" href="{if $newsitem->isRss}{$newsitem->rss_link}{else}{link action=view id=$newsitem->id}{/if}">{$_TR.read_more}<span> "{$newsitem->title}"</span></a>
		</div>
	</div>
	{/foreach}
	{permissions level=$smarty.const.UILEVEL_NORMAL}
		{if $morenews == 1 || $permissions.add_item == true || ($in_approval > 0 && $canview_approval_link == 1) || $permissions.view_unpublished == 1}
			<div class="moduleactions">
				{if $morenews == 1}
					<a class="viewmorenews" href="{link action=view_all_news}">{$_TR.view_all}</a>{br}
				{/if}
				{permissions level=$smarty.const.UILEVEL_NORMAL}
					{if $permissions.add_item == true}
						<a class="addnews" href="{link action=edit}">{$_TR.create_news}</a>{br}
					{/if}
					{if $in_approval > 0 && $canview_approval_link == 1}
						<a class="approvenews" href="{link module=workflow datatype=newsitem m=newsmodule s=$__loc->src action=summary}">{$_TR.view_approval}</a>{br}
					{/if}
					{if $permissions.view_unpublished == 1 }
						<a class="expirednews" href="{link action=view_expired}">{$_TR.view_expired}</a>{br}
					{/if}
				{/permissions}
			</div>
		{/if}
	{/permissions}
</div>


