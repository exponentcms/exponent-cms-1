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
{if $enable_rss == true}
        <a href="{rsslink}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}rss-feed.gif" title="Subscribe to this blog" /></a>
{/if}
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
{/if}
{if $permissions.configure == 1}
	<a href="{link action=configure _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}configure.png" title="{$_TR.alt_configure}" alt="{$_TR.alt_configure}" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}
{if $moduletitle != ""}<h1 class="moduletitle weblog_moduletitle">{$moduletitle}</h1>{/if}
{foreach from=$posts item=post}
<div>
<div>
<h1 class="itemtitle weblog_itemtitle">{$post->title}{if $post->is_draft} <span class="draft">(Draft)</span>{/if}</h1>
<div class="subheader weblog_subheader">Posted by {attribution user_id=$post->poster} on {$post->posted|format_date:$smarty.const.DISPLAY_DATE_FORMAT}</div>
<br />
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1 || $post->permissions.administrate == 1}
<a href="{link action=userperms _common=1 int=$post->id}">
	<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm_one}" alt="{$_TR.alt_userperm_one}" />
</a>
<a href="{link action=groupperms _common=1 int=$post->id}">
	<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm_one}" alt="{$_TR.alt_groupperm_one}" />
</a>
{/if}
{/permissions}
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.edit == 1 || $post->permissions.edit == 1}
<a class="mngmntlink weblog_mngmntlink" href="{link action=post_edit id=$post->id}">
	<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" border="0" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
</a>
{/if}
{if $permissions.delete == 1 || $post->permissions.delete == 1}
<a class="mngmntlink weblog_mngmntlink" href="{link action=post_delete id=$post->id}" onClick="return confirm('{$_TR.delete_confirm}');">
	<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" border="0" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
</a>
{/if}
{/permissions}
</div>
<div>{$post->body}</div>
<hr size="1" />
{if $config->allow_comments}
	<div class="comments" style="padding-left: 35px;">
    		<a href="{link action=view id=$post->id}">{$post->total_comments} Comment{if $post->total_comments != 1}s{/if} >></a>
	</div>
{/if}
</div>
{/foreach}
{if $total_posts > $config->items_per_page}
	<a class="mngmntlink weblog_mngmntlink" href="{link action=view_page page=1}">{$_TR.next}</a>
{/if}
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.post == 1}
<br />
<a class="mngmntlink weblog_mngmntlink" href="{link action=post_edit}">{$_TR.new_post}</a>
{/if}
{/permissions}
