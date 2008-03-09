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
	{include file="`$smarty.const.BASE`modules/common/views/_permission_icons.tpl"}
	<div class="itempermissions">	
		{permissions level=$smarty.const.UILEVEL_NORMAL}
		{if $newsitem->permissions.edit_item == 1}
			{if $newsitem->approved == 2} {* in ap *}
			<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.disabled.png" title="{$_TR.alt_edit_disabled}" alt="{$_TR.alt_edit_disabled}" />
			{else}
			<a class="mngmntlink news_mngmntlink" href="{link action=edit id=$newsitem->id}">
				<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
			</a>
			{/if}
		{/if}
		{if $newsitem->permissions.delete_item == 1}
			{if $newsitem->approved == 2} {* in ap *}
			<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.disabled.png" title="{$_TR.alt_delete_disabled}" alt="{$_TR.alt_delete_disabled}" />
			{else}
			<a class="mngmntlink news_mngmntlink" href="{link action=delete id=$newsitem->id}" onclick="return confirm('{$_TR.delete_confirm}');">
				<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
			</a>
			{/if}
		{/if}
		{/permissions}
	</div>
	<h1>{$newsitem->title}</h1>
	<div class="bodycopy"> 
		{if $newsitem->image!=""}<img src="{$smarty.const.URL_FULL}/thumb.php?file={$newsitem->image}&constraint=1&width=250&height=300" alt="{$newsitem->title}">{/if}
		{if $newsitem->edited eq 0}
                        {assign var='sortdate' value=$newsitem->real_posted}
                {else}
                        {assign var='sortdate' value=$newsitem->edited}
                {/if}

		<span class="date">{$sortdate|format_date:"%B %e"}</span>
		{$newsitem->body}
	</div>
</div>
