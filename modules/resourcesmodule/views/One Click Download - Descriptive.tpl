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

<div class="resourcesmodule one-click-download-descriptive">
	{include file="`$smarty.const.BASE`modules/common/views/_permission_icons.tpl"}
	{if $moduletitle != ""}<h1>{$moduletitle}</h1>{/if}
<table cellspacing="0" cellpadding="4" border="0" width="100%" rules="rows" frame="hsides" style="border: 1px solid lightgrey">
{foreach name=loop from=$resources item=resource}
{assign var=id value=$resource->id}
{assign var=fid value=$resource->file_id}
<tr><td td width="22" valign="top">
{if $files[$fid]->mimetype->icon != ""}
<img src="{$smarty.const.MIMEICON_RELATIVE}/{$files[$fid]->mimetype->icon}"/>
{/if}

</td>
<td align="left" valign="top">
<a class="mngmntlink resources_mngmntlink" href="{link action=download_resource id=$resource->id}" title="{$resource->name}">{$resource->name}</a>
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1 || $resource->permissions.administrate == 1}
<a class="mngmntlink resources_mngmntlink" href="{link action=userperms int=$resource->id _common=1}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm_one}" alt="{$_TR.alt_userperm_one}" /></a>
<a class="mngmntlink resources_mngmntlink" href="{link action=groupperms int=$resource->id _common=1}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm_one}" alt="{$_TR.alt_groupperm_one}" /></a>
{/if}
{/permissions}
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.edit == 1 || $resource->permissions.edit == 1}
<a class="mngmntlink resources_mngmntlink" href="{link action=edit id=$resource->id}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>
{/if}
{if $permissions.delete == 1 || $resource->permissions.delete == 1}
<a class="mngmntlink resources_mngmntlink" href="{link action=delete id=$resource->id}" onclick="return confirm('{$_TR.delete_confirm}');">
	<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
</a>
{/if}
{/permissions}
		<div style="padding-left: 20px;">
		{$resource->description}
		</div>
	</td>
</tr>
{/foreach}
</table>
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.post == 1 && $noupload != 1}
<a class="mngmntlink resources_mngmntlink" href="{link action=edit}">{$_TR.upload}</a>
{/if}
{/permissions}
{if $noupload == 1}
<div class="error">
{$_TR.no_upload}<br />
{if $uploadError == $smarty.const.SYS_FILES_FOUNDFILE}{$_TR.file_in_path}
{elseif $uploadError == $smarty.const.SYS_FILES_NOTWRITABLE}{$_TR.file_cant_mkdir}
{else}{$_TR.file_unknown}
{/if}
</div>
{/if}
</div>
