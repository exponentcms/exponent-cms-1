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

<div class="newsmodule viewsingle">
	{printer_friendly_link class="printer-friendly-link" text=$_TR.printer_friendly}
	{br}
	<h2>{$newsitem->title}</h2>
	<div class="itemactions">
		{permissions level=$smarty.const.UILEVEL_NORMAL}
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
			{if $permissions.manage_approval == 1 || $newsitem->permissions.manage_approval == true}
				<a class="mngmntlink news_mngmntlink" href="{link module=workflow datatype=newsitem m=newsmodule s=$__loc->src action=revisions_view id=$newsitem->id}" title="{$_TR.alt_revisions}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}revisions.png" title="{$_TR.alt_revisions}" alt="{$_TR.alt_revisions}"/></a>
			{/if}
		{/permissions}	
	</div>
	<div class="post-footer">
		{if $newsitem->edited eq 0 || $config->sortfield == "publish"}
			{*assign var='sortdate' value=$newsitem->real_posted*}
			{assign var='sortdate' value=$newsitem->posted}
			{assign var='typepost' value=$_TR.posted}
			{assign var='whopost'  value=$newsitem->poster}
		{else}
			{assign var='sortdate' value=$newsitem->edited}
			{assign var='typepost' value=$_TR.updated}
			{assign var='whopost'  value=$newsitem->editor}
		{/if}	
		Read {$newsitem->reads} times |
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
	<div class="bodycopyfull"> 
		{if $newsitem->image!=""}<img src="{$smarty.const.URL_FULL}/thumb.php?file={$newsitem->image}&constraint=1&width=250&height=300" alt="{$newsitem->title}">{/if}
		{$newsitem->body}
	</div>
	<div class="itemactions">
		{if $prev_item != 0 || $next_item != 0}
			<div class="paging">
				<hr>
				<span class="previous">
					{if $prev_item != 0} 
						<a class="weblog_mngmntlink" href="{link action=view id=$prev_item->id}">{$prev_item->title}</a>&nbsp;{$_TR.previous}
					{else}
						&nbsp;
					{/if}
				</span>
				<span class="next">
					{if $next_item!=0}
						{$_TR.next}&nbsp;<a class="weblog_mngmntlink" href="{link action=view id=$next_item->id}">{$next_item->title}</a>
					{else}
						&nbsp;
					{/if}
				</span>
			</div>
		{/if}	
	</div>
</div>
