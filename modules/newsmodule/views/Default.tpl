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
 {permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="Assign user permissions on this News Feed System" alt="Assign user permissions on this News Feed System" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="Assign group permissions on this News Feed System" alt="Assign group permissions on this News Feed System" /></a>
{/if}
{if $permissions.configure == 1}
	<a href="{link action=configure _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}configure.png" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}

{if $moduletitle != ""}<div class="moduletitle news_moduletitle">{$moduletitle}</div>{/if}
{foreach from=$news item=newsitem}
	<div>
		<div class="itemtitle news_itemtitle">{$newsitem->title}</div>
		{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
		{if $permissions.administrate == true || $newsitem->permissions.administrate == true}
			<a href="{link action=userperms int=$newsitem->id _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="Assign user permissions on this News Item" alt="Assign user permissions on this News Item" /></a>&nbsp;
			<a href="{link action=groupperms int=$newsitem->id _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="Assign group permissions on this News Item" alt="Assign group permissions on this News Item" /></a>
		{/if}
		{/permissions}
		{permissions level=$smarty.const.UILEVEL_NORMAL}
		{if $permissions.edit_item == true || $newsitem->permissions.edit_item == true}
			{if $newsitem->approved == 2} {* in ap *}
			<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}edit.disabled.png" title="Editting Disabled - News Item In Approval" alt="Editting Disabled - News Item In Approval" />
			{else}
			<a class="mngmntlink news_mngmntlink" href="{link action=edit id=$newsitem->id}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}edit.png" title="Edit this News Item" alt="Edit this News Item" /></a>
			{/if}
		{/if}
		{if $permissions.delete_item == true || $newsitem->permissions.delete_item == true}
			{if $newsitem->approved == 2} {* in ap *}
			<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.disabled.png" title="Deleting Disabled - News Item In Approval" alt="Deleting Disabled - News Item In Approval" />
			{else}
			<a onClick="return confirm('Are you sure you want to delete this news item?');" class="mngmntlink news_mngmntlink" href="{link action=delete id=$newsitem->id}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.png" title="Delete this News Item" alt="Delete this News Item" /></a>
			{/if}
		{/if}
		{if $permissions.manage_approval == 1}
			<a class="mngmntlink news_mngmntlink" href="{link module=workflow datatype=newsitem m=newsmodule s=$__loc->src action=revisions_view id=$newsitem->id}" title="View Revision History for this News Item" alt="View Revision History for this News Item">Revisions</a>
		{/if}
		{/permissions}
		<div style="padding-left: 15px;">
		{$newsitem->body|summarize:"html":"para"}
		</div>
	</div>
	<a class="mngmntlink news_mngmntlink" href="{link action=view id=$newsitem->id}">Read More...</a>
	<br /><br />
{/foreach}
{if $morenews == 1}
<a href="{link action=view_all_news}">More News...</a>
{/if}
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.add_item == true}
	<br /><a class="mngmntlink news_mngmntlink" href="{link action=edit}">Create News</a>
{/if}
{if $in_approval > 0 && $canview_approval_link == 1}
	<br /><a class="mngmntlink news_mngmntlink" href="{link module=workflow datatype=newsitem m=newsmodule s=$__loc->src action=summary}">View Approval</a>
{/if}
{if $permissions.view_unpublished == 1}
	<br /><a class="mngmntlink news_mngmntlink" href="{link action=view_expired}">Unpublished / Expired News</a>
{/if}
{/permissions}
