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
	<a href="{link action=userperms _common=1}"><img border="0" src="{$smarty.const.ICON_RELATIVE}userperms.gif" title="Assign user permissions on this Resource Manager" alt="Assign user permissions on this Resource Manager" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}"><img border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.gif" title="Assign group permissions on this Resource Manager" alt="Assign group permissions on this Resource Manager" /></a>
{/if}
{if $permissions.configure == 1}
	<a href="{link action=configure _common=1}"><img border="0" src="{$smarty.const.ICON_RELATIVE}configure.gif" title="Change the configuration of this Resource Manager" alt="Change the configuration of this Resource Manager" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}
{if $moduletitle != ""}<div class="moduletitle resource_moduletitle">{$moduletitle}</div>{/if}
<table cellspacing="0" cellpadding="4" border="0" width="100%" rules="rows" frame="hsides" style="border: 1px solid lightgrey">
{foreach from=$resources item=resource}
{assign var=id value=$resource->id}
{assign var=fid value=$resource->file_id}
<tr>
	<td valign="top">
		<a class="mngmntlink resources_mngmntlink" href="{link action=view id=$resource->id}">{$resource->name}</a><br />
		{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
		{if $permissions.administrate == 1 || $resource->permissions.administrate == 1}
		<a class="mngmntlink resources_mngmntlink" href="{link action=userperms int=$resource->id _common=1}"><img src="{$smarty.const.ICON_RELATIVE}userperms.gif" border="0" title="Assign user permissions on this Resource" alt="Assign user permissions on this Resource" /></a>
		<a class="mngmntlink resources_mngmntlink" href="{link action=groupperms int=$resource->id _common=1}"><img src="{$smarty.const.ICON_RELATIVE}groupperms.gif" border="0" title="Assign group permissions on this Resource" alt="Assign group permissions on this Resource" /></a>
		{/if}
		{/permissions}
		{permissions level=$smarty.const.UILEVEL_NORMAL}
		{if $permissions.edit == 1 || $resource->permissions.edit == 1}
		<a class="mngmntlink resources_mngmntlink" href="{link action=edit id=$resource->id}"><img src="{$smarty.const.ICON_RELATIVE}edit.gif" border="0" title="Edit this Resource" alt="Edit this Resource" /></a>
		{/if}
		{if $permissions.delete == 1 || $resource->permissions.delete == 1}
		<a class="mngmntlink resources_mngmntlink" href="{link action=delete id=$resource->id}" onClick="return confirm('Are you sure you want to delete this Resource?');">
			<img src="{$smarty.const.ICON_RELATIVE}delete.gif" border="0" title="Delete this Resource" alt="Delete this Resource" />
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
{if $permissions.post == 1}
<a class="mngmntlink resources_mngmntlink" href="{link action=edit}">Upload Resource</a>
{/if}
{/permissions}