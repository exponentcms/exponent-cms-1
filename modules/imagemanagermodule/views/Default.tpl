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
{if $permissions.configure == 1 or $permissions.administrate == 1 or $permissions.post == 1 or $permissions.edit == 1 or $permissions.delete == 1 || $smarty.const.PREVIEW_READONLY}
{if $moduletitle != ""}<div class="moduletitle imagemanager_moduletitle">{$moduletitle}</div>{/if}
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="Assign user permissions on this Image Manager" alt="Assign user permissions on this Image Manager" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="Assign group permissions on this Image Manager" alt="Assign group permissions on this Image Manager" /></a>
{/if}
{if $permissions.configure == 1}
	<a href="{link action=configure _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}configure.png" title="Change the configuration of this Image Manager" alt="Change the configuration of this Image Manager" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}
<table cellpadding="2" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header imagemanager_header">Preview</td>
		<td class="header imagemanager_header">Name</td>
		<td class="header imagemanager_header">&nbsp;</td>
	</tr>
{foreach from=$items item=item}
{assign var=fid value=$item->file_id}
	<tr>
		<td>
			{if $smarty.const.SELECTOR == 1}
			<a class="mngmntlink imagemanager_mngmntlink" href="{$smarty.const.PATH_RELATIVE}modules/imagemanagermodule/picked.php?url={$files[$fid]->directory}/{$files[$fid]->filename}">
				<img src="{$smarty.const.PATH_RELATIVE}thumb.php?base={$smarty.const.BASE}&file={$files[$fid]->directory}/{$files[$fid]->filename}&scale={$item->scale}" border="0" title="Use this Image" alt="Use this Image"/>
			</a>
			{else}
			<a class="mngmntlink imagemanager_mngmntlink" href="{link action=view id=$item->id}">
				<img src="{$smarty.const.PATH_RELATIVE}thumb.php?base={$smarty.const.BASE}&file={$files[$fid]->directory}/{$files[$fid]->filename}&scale={$item->scale}" border="0" title="View this Image" alt="View this Image"/>
			</a>
			{/if}
		</td>
		<td>
			{if $smarty.const.SELECTOR == 1}
			<a class="mngmntlink imagemanager_mngmntlink" href="{$smarty.const.PATH_RELATIVE}modules/imagemanagermodule/picked.php?url={$files[$fid]->directory}/{$files[$fid]->filename}" title="Use this Image" alt="Use this Image">
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
			<a class="mngmntlink imagemanager_mngmntlink" href="{link action=edit id=$item->id}" title="Edit this Image" alt="Edit this Image" />
				<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" border="0" />
			</a>
			{/if}
			{if $permissions.delete == 1}
			<a class="mngmntlink imagemanager_mngmntlink" href="{link action=delete id=$item->id}" onClick="return confirm('Are you sure you want to delete this Image?');">
				<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" border="0" title="Delete this Image" alt="Delete this Image" />
			</a>
			{/if}
			{/permissions}
		</td>
	</tr>
{foreachelse}
	<tr><td align="center" colspan="3"><i>No uploaded images</i></td></tr>
{/foreach}
</table>
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.post == 1 && $noupload != 1}
<a class="mngmntlink imagemanager_mngmntlink" href="{link action=edit}">Upload Image</a>
{/if}
{/permissions}

{if $noupload == 1}
<div class="error">
Uploads have been disabled.<br />
{if $uploadError == $smarty.const.SYS_FILES_FOUNDFILE}Found a file in the directory path when creating the directory to store the files in.
{elseif $uploadError == $smarty.const.SYS_FILES_NOTWRITABLE}Unable to create directory to store files in.
{else}An unknown error has occurred.  Please contact the Exponent Developers.
{/if}
</div>
{/if}

{else}
{/if}{* If check - show or not *}