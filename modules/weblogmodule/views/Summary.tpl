{*
 *
 * Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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
	<a href="{link action=userperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="Assign user permissions on this Weblog" alt="Assign user permissions on this Weblog" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="Assign group permissions on this Weblog" alt="Assign group permissions on this Weblog" /></a>
{/if}
{if $permissions.configure == 1}
	<a href="{link action=configure _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}configure.png" title="Change the configuration of this Weblog" alt="Change the configuration of this Weblog" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}
{if $moduletitle != ""}<div class="moduletitle weblog_moduletitle">{$moduletitle}</div>{/if}
{foreach from=$posts item=post}
<div>
<div class="itemtitle weblog_itemtitle">{$post->title}
<br />
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1 || $post->permissions.administrate == 1}
<a href="{link action=userperms _common=1 int=$post->id}">
	<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="Assign permissions on this Weblog Post" alt="Assign permissions on this Weblog Post" />
</a>
<a href="{link action=groupperms _common=1 int=$post->id}">
	<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="Assign group permissions on this Weblog Post" alt="Assign group permissions on this Weblog Post" />
</a>
{/if}
{/permissions}
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.edit == 1 || $post->permissions.edit == 1}
<a class="mngmntlink weblog_mngmntlink" href="{link action=post_edit id=$post->id}">
	<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" border="0" title="Edit this Weblog Post" alt="Edit this Weblog Post" />
</a>
{/if}
{if $permissions.delete == 1 || $post->permissions.delete == 1}
<a class="mngmntlink weblog_mngmntlink" href="{link action=post_delete id=$post->id}" onClick="return confirm('Are you sure you want to delete this Weblog Post?');">
	<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" border="0" title="Delete this Weblog Post" alt="Delete this Weblog Post" />
</a>
{/if}
{/permissions}
</div>
<div class="subheader weblog_subheader">Posted by {attribution user_id=$post->poster} on {$post->posted|format_date:$smarty.const.DISPLAY_DATE_FORMAT}</div>
<div>{$post->body|summarize:html:para}</div>
{if $smarty.const.MEANINGFUL_URLS}
<div><a href="{$smarty.const.URL_FULL}content/blog/{$post->internal_name}">Read More</a></div>
{else}
<div><a href="{$smarty.const.URL_FULL}content/blog.php?id={$post->id}">Read More</a></div>
{/if}
<hr size="1" />
</div>
{/foreach}
{if $total_posts > $config->items_per_page}
	<a class="mngmntlink weblog_mngmntlink" href="{link action=view_page page=1}">Next</a>
{/if}
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.post == 1}
<br />
<a class="mngmntlink weblog_mngmntlink" href="{link action=post_edit}">New Post</a>
{/if}
{/permissions}