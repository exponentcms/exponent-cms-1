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
<table cellpadding="2" cellspacing="0" width="100%" border="0">
<tr>
	<td class="header banner_header">Banner</td>
	<td class="header banner_header">Affiliate</td>
	<td class="header banner_header">&nbsp;</td>
</tr>
{foreach from=$banners item=banner}
{assign var=aid value=$banner->affiliate_id}
{assign var=fid value=$banner->file_id}
<tr>
	<td valign="top">
		{$banner->name}<br />
		<img src="{$smarty.const.PATH_RELATIVE}{$files[$fid]->directory}/{$files[$fid]->filename}" />
	</td>
	<td valign="top">{$affiliates[$aid]}</td>
	<td valign="top">
		<a class="mngmntlink banner_mngmntlink" href="{link action=ad_edit id=$banner->id}">
			<img src="{$smarty.const.ICON_RELATIVE}edit.png" border="0" />
		</a>
		<a class="mngmntlink banner_mngmntlink" href="{link action=ad_delete id=$banner->id}" onClick="return confirm('Are you sure you want to delete \'{$banner->name}\'?');">
			<img src="{$smarty.const.ICON_RELATIVE}delete.png" border="0" />
		</a>
	</td>
</tr>
{/foreach}
</table>
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.manage == 1 && $noupload != 1}
<a class="mngmntlink banner_mngmntlink" href="{link action=ad_edit}">New Banner</a>
{/if}
{/permissions}

{if $noupload == 1}
<div class="error">
Uploads have been disabled.<br />
{if $uploadError == $smarty.const.SYS_FILES_FOUNDFILE}Found a file in the directory path when creating the directory to store the files in.
{else if $uploadError == $smarty.const.SYS_FILES_NOTWRITABLE}Unable to create directory to store files in.
{else}An unknown error has occurred.  Please contact the Exponent Developers.
{/if}
</div>
{/if}
