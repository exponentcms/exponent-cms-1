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
{if $item->permissions.administrate == 1}
	<a href="{link action=userperms int=$item->id _common=1}"><img border="0" src="{$smarty.const.ICON_RELATIVE}userperms.gif" /></a>&nbsp;
	<a href="{link action=groupperms int=$item->id _common=1}"><img border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.gif" /></a>
	<a href="{$singlelinkbase}userperms&int={$item->id}&_common=1"><img border="0" src="{$smarty.const.ICON_RELATIVE}userperms.gif" /></a>&nbsp;
	<a href="{$singlelinkbase}groupperms&int={$item->id}&_common=1"><img border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.gif" /></a>
{/if}
{if $item->permissions.edit_item == 1}
	<a class="mngmntlink news_mngmntlink" href="{link action=edit id=$item->id}">Edit</a>
{/if}
{if $item->permissions.delete_item == 1}
	<a class="mngmntlink news_mngmntlink" href="{link action=delete id=$item->id}">Delete</a>
{/if}
<div class="itemtitle news_itemtitle">{$item->title}</div>
<div class="itembody news_itembody">
{$item->body}
</div>
</div>