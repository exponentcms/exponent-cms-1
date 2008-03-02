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
 * $Id: Default - No Icons.tpl 1319 2006-08-22 08:24:40 -0500 (Tue, 22 Aug 2006) jacobmesu $
 *}
 {permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}
{if $config->enable_podcasting == 1}
        <a href="{podcastlink}">Podcast</a>
{/if}
{if $moduletitle != ""}<div class="moduletitle resource_moduletitle">{$moduletitle}</div>{/if}
<table cellpadding="0" cellspacing="0" border="0" width="100%">
{foreach from=$resources item=resource}
{assign var=id value=$resource->id}
{assign var=fid value=$resource->file_id}
<tr><td>
<a class="mngmntlink resources_mngmntlink" href="{link action=view id=$resource->id}">{$resource->name}</a>
</td><td align="left" valign="top">
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1 || $resource->permissions.administrate == 1}
<a class="mngmntlink resources_mngmntlink" href="{link action=userperms int=$resource->id _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>
<a class="mngmntlink resources_mngmntlink" href="{link action=groupperms int=$resource->id _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
{/if}
{/permissions}
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.edit == 1 || $resource->permissions.edit == 1}
<a class="mngmntlink resources_mngmntlink" href="{link action=edit id=$resource->id}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>
{/if}
{if $permissions.delete == 1 || $resource->permissions.delete == 1}
<a class="mngmntlink resources_mngmntlink" href="{link action=delete id=$resource->id}" onclick="return confirm('Are you sure you want to delete this Resource?');">
	<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
</a>
{/if}
{/permissions}
</td></tr>
{/foreach}
</table>
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.post == 1 && $noupload != 1}
<a class="mngmntlink resources_mngmntlink" href="{link action=edit}">Upload Resource</a>
{/if}
{/permissions}
{if $noupload == 1}
<div class="error">
Uploads have been disabled.<br />
{if $uploadError == $smarty.const.SYS_FILES_FOUNDFILE}{$smarty.const.TR_FILEMANAGER_FILEFOUNDINPATH}
{elseif $uploadError == $smarty.const.SYS_FILES_NOTWRITABLE}{$smarty.const.TR_FILEMANAGER_CANTMKDIR}
{else}{$smarty.const.TR_FILEMANAGER_UNKNOWNERROR}
{/if}
</div>
{/if}
