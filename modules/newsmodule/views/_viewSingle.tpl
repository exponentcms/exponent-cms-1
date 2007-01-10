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
<div class="fullitem news_fullitem">
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $newsitem->permissions.administrate == 1}
	<a href="{link action=userperms int=$newsitem->id _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>&nbsp;
	<a href="{link action=groupperms int=$newsitem->id _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
{/if}
{/permissions}
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $newsitem->permissions.edit_item == 1}
	{if $n->approved == 2} {* in ap *}
	<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}edit.disabled.png" title="{$_TR.alt_edit_disabled}" alt="{$_TR.alt_edit_disabled}" />
	{else}
	<a class="mngmntlink news_mngmntlink" href="{link action=edit id=$newsitem->id}">
		<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" border="0" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
	</a>
	{/if}
{/if}
{if $newsitem->permissions.delete_item == 1}
	{if $n->approved == 2} {* in ap *}
	<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.disabled.png" title="{$_TR.alt_delete_disabled}" alt="{$_TR.alt_delete_disabled}" />
	{else}
	<a class="mngmntlink news_mngmntlink" href="{link action=delete id=$newsitem->id}" onClick="return confirm('{$_TR.delete_confirm}');">
		<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" border="0" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
	</a>
	{/if}
{/if}
{/permissions}
<div class="itemtitle news_itemtitle">{$newsitem->title}</div>
<div class="itembody news_itembody">
posted by {attribution user_id=$newsitem->poster} on {$newsitem->real_posted|format_date:$smarty.const.DISPLAY_DATE_FORMAT}<br /><br />
{$newsitem->body}
</div>
</div>
