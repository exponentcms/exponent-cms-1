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
{if $show == 1}
	{if $permissions.configure == 1 or $permissions.administrate == 1 or $permissions.post == 1 or $permissions.edit == 1 or $permissions.delete == 1 || $smarty.const.PREVIEW_READONLY == 1}
		{if $moduletitle != ""}<div class="moduletitle imagemanager_moduletitle">{$moduletitle}</div>{/if}
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
		<table cellpadding="2" cellspacing="0" border="0" width="100%">
			<tr>
				<td class="header imagemanager_header">{$_TR.preview}</td>
				<td class="header imagemanager_header">{$_TR.name}</td>
				<td class="header imagemanager_header">&nbsp;</td>
			</tr>
		{foreach from=$items item=item}
			{assign var=fid value=$item->file_id}
			<tr>
				<td>
					{if $smarty.const.SELECTOR == 1}
					<a class="mngmntlink imagemanager_mngmntlink" href="{$smarty.const.PATH_RELATIVE}modules/imagemanagermodule/picked.php?url={$files[$fid]->directory}/{$files[$fid]->filename}">
						{if $item->scale == 100}
						<img src="{$smarty.const.PATH_RELATIVE}{$files[$fid]->directory}/{$files[$fid]->filename}" border="0" title="{$_TR.use_image}" alt="{$_TR.use_image}"/>
						{else}
						<img src="{$smarty.const.PATH_RELATIVE}thumb.php?file={$files[$fid]->directory}/{$files[$fid]->filename}&scale={$item->scale}" border="0" title="{$_TR.use_image}" alt="{$_TR.use_image}"/>
						{/if}
					</a>
					{else}
					<a class="mngmntlink imagemanager_mngmntlink" href="{link action=view id=$item->id}">
						{if $item->scale == 100}
						<img src="{$smarty.const.PATH_RELATIVE}{$files[$fid]->directory}/{$files[$fid]->filename}" border="0" title="{$_TR.view_image}" alt="{$_TR.view_image}"/>
						{else}
						<img src="{$smarty.const.PATH_RELATIVE}thumb.php?file={$files[$fid]->directory}/{$files[$fid]->filename}&scale={$item->scale}" border="0" title="{$_TR.view_image}" alt="{$_TR.view_image}"/>
						{/if}
					</a>
					{/if}
				</td>
				<td>
					{if $smarty.const.SELECTOR == 1}
					<a class="mngmntlink imagemanager_mngmntlink" href="{$smarty.const.PATH_RELATIVE}modules/imagemanagermodule/picked.php?url={$files[$fid]->directory}/{$files[$fid]->filename}" title="{$_TR.use_image}" alt="{$_TR.use_image}">
						{$item->name}
					</a>
					{else}
					<a class="mngmntlink imagemanager_mngmntlink" href="{link action=view id=$item->id}">
						{$item->name}
					</a>
					{/if}
				</td>
				<td>
					{permissions level=$smarty.const.UILEVEL_NORMAL}
					{if $permissions.edit == 1}
					<a class="mngmntlink imagemanager_mngmntlink" href="{link action=edit id=$item->id}" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
						<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" border="0" />
					</a>
					{/if}
					{if $permissions.delete == 1}
					<a class="mngmntlink imagemanager_mngmntlink" href="{link action=delete id=$item->id}" onClick="return confirm('{$_TR.delete_confirm}');">
						<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" border="0" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
					</a>
					{/if}
					{/permissions}
				</td>
			</tr>
		{foreachelse}
			<tr><td align="center" colspan="3"><i>{$_TR.no_images}</i></td></tr>
		{/foreach}
		</table>
		{permissions level=$smarty.const.UILEVEL_NORMAL}
			{if $permissions.post == 1 && $noupload != 1}
				<a class="mngmntlink imagemanager_mngmntlink" href="{link action=edit}">{$_TR.upload}</a>
			{/if}
		{/permissions}
	
		{if $noupload == 1}
			<div class="error">
				{$_TR.uploads_disabled}<br />
				{if $uploadError == $smarty.const.SYS_FILES_FOUNDFILE}{$_TR.file_in_path}
				{elseif $uploadError == $smarty.const.SYS_FILES_NOTWRITABLE}{$_TR.file_cant_mkdir}
				{else}{$_TR.file_unknown}
				{/if}
			</div>
		{/if}
	
	{else}
	{/if}{* If check - show or not *}
{/if}
