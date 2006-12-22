{*
 * Copyright (c) 2005-2006 Dirk Olten, http://www.extrabyte.de
 *
 * This file is part of Exponent Guestbookmodule
 *
 * Exponent is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * GPL: http://www.gnu.org/licenses/gpl.txt
 *}
 
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
{if $moduletitle != ""}<div class="moduletitle">{$moduletitle}</div>{/if}
<br />
<a class="mngmntlink guestbook_mngmntlink" href="{link action=post_edit}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}add.gif" title="{$_TR.new_post}" alt="{$_TR.new_post}" align="absmiddle" style="margin-right: 5px;" />{$_TR.new_post}</a>

<div style="margin-left: 20px; margin-right: 20px">
{foreach from=$posts item=post}
	<div style="margin-top: 15px; border: 1px solid silver;">
		<div style="font-weight:bold; padding: 5px;">
			<div style="font-weight:normal;float:right;display:inline;">
				{$_TR.txt_posted_by}
				{if $post->url && $post->url != 'http://'}
					<a href="{$post->url}" target="_blank" title="{$_TR.txt_visit_site1}{$post->name}{$_TR.txt_visit_site2}">
				{/if}
				{$post->name}
				{if $post->url && $post->url != 'http://'}</a>{/if}
				{$_TR.txt_on_date} {$post->posted|format_date:$smarty.const.DISPLAY_DATE_FORMAT}
			</div>
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
<a class="mngmntlink guestbook_mngmntlink" href="{link action=post_edit id=$post->id}">
	<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" border="0" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
</a>
{/if}
{if $permissions.delete == 1 || $post->permissions.delete == 1}
<a class="mngmntlink guestbook_mngmntlink" href="{link action=post_delete id=$post->id}" onClick="return confirm('{$_TR.delete_confirm}');">
	<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" border="0" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
</a>
{/if}
{/permissions}

			{$post->title}
		</div>
<div style="background-color: #dfdfdf; padding: 5px">{$post->body}</div>
{if $config->allow_comments}
	<div style="background-color: silver;">
		{permissions level=$smarty.const.UILEVEL_NORMAL}
		{if $permissions.comment == 1 || $post->permissions.comment == 1}
		<a class="mngmntlink guestbook_mngmntlink" href="{link action=comment_edit parent_id=$post->id}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}package.png" title="{$_TR.alt_reply}" alt="{$_TR.alt_reply}" align="absmiddle" style="margin-right: 5px;" />{$_TR.txt_reply}</a>
		{/if}
		{/permissions}
		{if $post->comments}
					<div style="background-color: #666666;border:1px solid #444444; padding:5px;font-weight:bold;color: #ffffff">{$_TR.txt_replys}:
		{/if}
		{foreach from=$post->comments item=comment}
			<div style="background-color: #dfdfdf;margin-top:5px;">
				<div style="font-weight:bold; padding: 5px;">
					<div style="font-weight:normal;float:right;display:inline;">
						{$_TR.txt_comment_by} <b>{attribution user_id=$comment->poster}</b> {$_TR.txt_on_date} {$comment->posted|format_date:$smarty.const.DISPLAY_DATE_FORMAT}
					</div>

					{permissions level=$smarty.const.UILEVEL_NORMAL}
					{if $permissions.edit_comments == 1 || $post->permissions.edit_comments == 1}
						<a class="mngmntlink guestbook_mngmntlink" href="{link action=comment_edit id=$comment->id parent_id=$post->id}">
							<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" border="0" title="{$_TR.alt_edit_comment}" alt="{$_TR.alt_edit_comment}" />
						</a>
					{/if}
					{if $permissions.delete_comments == 1 || $post->permissions.delete_comments == 1}
						<a class="mngmntlink guestbook_mngmntlink" href="{link action=comment_delete id=$comment->id parent_id=$post->id}" onClick="return confirm('{$_TR.delete_comment_confirm}');">
							<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" border="0" title="{$_TR.alt_delete_comment}" alt="{$_TR.alt_delete_comment}" />
						</a>
					{/if}
					{/permissions}
					{$comment->title}
				</div>
				<div style="background-color: #ffffff; padding: 5px;font-weight:normal;">{$comment->body}</div>
			</div>
		{/foreach}
		{if $post->comments}
			</div>
		{/if}

	</div>
{/if}
</div>
{/foreach}
<div style="margin-left: 20px; margin-right: 20px;text-align:center">
{if $page != 0}
	{math equation="x-1" x=$page assign=prevpage}
	<a class="mngmntlink guestbook_mngmntlink" href="{link action=_view_page page=$prevpage}">
	<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}left.png" title="{$_TR.previous}" alt="{$_TR.previous}" align="absmiddle" style="margin-right: 5px;" />
	{$_TR.previous}
	</a>&nbsp;&nbsp;
{/if}
{if $shownext}
	{math equation="x+1" x=$page assign=nextpage}
	<a class="mngmntlink guestbook_mngmntlink" href="{link action=_view_page page=$nextpage}">
	{$_TR.next}
	<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}right.png" title="{$_TR.next}" alt="{$_TR.next}" align="absmiddle" style="margin-right: 5px;" />
	</a>
{/if}
</div>