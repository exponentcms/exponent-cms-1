{*
 *
 * Copyright 2004 James Hunt and OIC Group, Inc.
 *
 * This file is part of Exponent
 *
 * Exponent is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * Exponent is distributed in the hope that it
 * will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR
 * PURPOSE.  See the GNU General Public License
 * for more details.
 *
 * You should have received a copy of the GNU
 * General Public License along with Exponent; if
 * not, write to:
 *
 * Free Software Foundation, Inc.,
 * 59 Temple Place,
 * Suite 330,
 * Boston, MA 02111-1307  USA
 *
 * $Id$
 *}
<div class="fullitem news_fullitem">
{if $newsitem->permissions.administrate == 1}
	<a href="{link action=userperms int=$newsitem->id _common=1}"><img border="0" src="{$smarty.const.ICON_RELATIVE}userperms.gif" title="Assign user permissions on this News Item" alt="Assign user permissions on this News Item" /></a>&nbsp;
	<a href="{link action=groupperms int=$newsitem->id _common=1}"><img border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.gif" title="Assign group permissions on this News Item" alt="Assign group permissions on this News Item" /></a>
{/if}
{if $newsitem->permissions.edit_item == 1}
	{if $n->approved == 2} {* in ap *}
	<img border="0" src="{$smarty.const.ICON_RELATIVE}edit.disabled.gif" title="Editting Disabled - News Item In Approval" alt="Editting Disabled - News Item In Approval" />
	{else}
	<a class="mngmntlink news_mngmntlink" href="{link action=edit id=$newsitem->id}">
		<img src="{$smarty.const.ICON_RELATIVE}edit.gif" border="0" title="Edit this News Item" alt="Edit this News Item" />
	</a>
	{/if}
{/if}
{if $newsitem->permissions.delete_item == 1}
	{if $n->approved == 2} {* in ap *}
	<img border="0" src="{$smarty.const.ICON_RELATIVE}delete.disabled.gif" title="Editting Disabled - News Item In Approval" alt="Deleting Disabled - News Item In Approval" />
	{else}
	<a class="mngmntlink news_mngmntlink" href="{link action=delete id=$newsitem->id}" onClick="return confirm('Are you sure you want to delete this News Item?');">
		<img src="{$smarty.const.ICON_RELATIVE}delete.gif" border="0" title="Delete this News Item" alt="Delete this News Item" />
	</a>
	{/if}
{/if}
<div class="itemtitle news_itemtitle">{$newsitem->title}</div>
<div class="itembody news_itembody">
posted by {attribution user_id=$newsitem->poster}<br /><br />
{$newsitem->body}
</div>
</div>