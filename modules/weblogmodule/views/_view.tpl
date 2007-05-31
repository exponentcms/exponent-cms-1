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
{*
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
{/if}
{if $permissions.configure == 1}
	<a href="{link action=configure _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}configure.png" title="{$_TR.alt_change_config}" alt="{$_TR.alt_change_config}" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}
*}

<div style="margin-top: 15px;">
<h1 class="itemtitle weblog_itemtitle">{$this_post->title}{if $post->is_draft} <span class="draft">(Draft)</span>{/if}</h1>
<div class="subheader weblog_subheader">Posted by {attribution user_id=$this_post->poster} on {$this_post->posted|format_date:$smarty.const.DISPLAY_DATE_FORMAT}</div>
<br />
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1 || $this_post->permissions.administrate == 1}
<a href="{link action=userperms _common=1 int=$this_post->id}">
	<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm_one}" alt="{$_TR.alt_userperm_one}" />
</a>
<a href="{link action=groupperms _common=1 int=$this_post->id}">
	<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm_one}" alt="{$_TR.alt_groupperm_one}" />
</a>
{/if}
{/permissions}
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.edit == 1 || $this_post->permissions.edit == 1}
<a class="mngmntlink weblog_mngmntlink" href="{link action=post_edit id=$this_post->id}">
	<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
</a>
{/if}
{if $permissions.delete == 1 || $this_post->permissions.delete == 1}
<a class="mngmntlink weblog_mngmntlink" href="{link action=post_delete id=$this_post->id}" onclick="return confirm('{$_TR.delete_confirm}');">
	<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
</a>
{/if}
{/permissions}
</div>
<div>{$this_post->body}</div>
{if $config->allow_comments}
	<div class="comments">
	{if $post->is_draft}
		<i>{$_TR.draft_desc}</i>
	{else}
    <br />
    <div class="weblog_itemtitle"><a name="comments">{$this_post->total_comments} comment{if $this_post->total_comments != 1}s{/if} to "{$this_post->title}"</a></div>
		{foreach from=$this_post->comments item=comment}
			<div class="weblog_comment_{cycle values="odd,even"}">
        <div class="weblog_comment_body">{$comment->body}
				{permissions level=$smarty.const.UILEVEL_NORMAL}
				{if $permissions.edit_comments == 1 || $this_post->permissions.edit_comments == 1}
				<a class="mngmntlink weblog_mngmntlink" href="{link action=comment_edit id=$comment->id parent_id=$this_post->id}">
					<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit_comment}" alt="{$_TR.alt_edit_comment}" />
				</a>
				{/if}
				{if $permissions.delete_comments == 1 || $this_post->permissions.delete_comments == 1}
				<a class="mngmntlink weblog_mngmntlink" href="{link action=comment_delete id=$comment->id parent_id=$this_post->id}" onclick="return confirm('{$_TR.delete_comment_confirm}');">
					<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete_comment}" alt="{$_TR.alt_delete_comment}" />
				</a>
				{/if}
				{/permissions}
				</div>
				<div class="weblog_comment_attribution">
          <img style="border:none;" src="{$smarty.const.ICON_RELATIVE}arrow_right.gif" title="{$_TR.alt_arrow_right}" alt="{$_TR.alt_arrow_right}" />
          Posted by {$comment->name} on {$comment->posted|format_date:$smarty.const.DISPLAY_DATE_FORMAT}
        </div>
			</div>
		{/foreach}
	{/if}
	</div>
{/if}
<div class="weblog_itemtitle">Leave a comment</div>
{$form_html}
<br /><br />
{if $prev_post != 0 || $next_post != 0}
<table border="0" cellpadding="3" cellspacing="0" align="left" width="100%">
<tr><td align="left">&lt;&lt;Previous Post</td><td style="text-align: right;padding-right:10px;">Next Post&gt;&gt;</td></tr>
<tr><td align="left">
{if $prev_post != 0}
	<a class="mngmntlink weblog_mngmntlink" href="{link action=view id=$prev_post->id}">{$prev_post->title}</a>
{else}
&nbsp;
{/if}
</td>
<td style="text-align: right; padding-right:10px;">
{if $next_post != 0}
	<a class="mngmntlink weblog_mngmntlink" href="{link action=view id=$next_post->id}">{$next_post->title}</a>
{else}
  &nbsp;
{/if}
</td></tr></table>
{/if}

