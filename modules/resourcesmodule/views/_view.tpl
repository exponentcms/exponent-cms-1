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
<table>
<tr><td width="22" valign="top">
	<!-- {$file->mimetype} -->
	{if $mimetype->icon != ""}
	<img src="{$smarty.const.MIMEICON_RELATIVE}{$mimetype->icon}"/>
	{/if}
</td>
<td>
	<b>{$resource->name}</b><br />
	{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
	{if $permissions.administrate == 1 || $resource->permissions.administrate == 1}
	<a class="mngmntlink resources_mngmntlink" href="{link action=userperms int=$resource->id _common=1}"><img src="{$smarty.const.ICON_RELATIVE}userperms.png" border="0" title="Assign user permissions on this Resource" alt="Assign user permissions on this Resource" /></a>
	<a class="mngmntlink resources_mngmntlink" href="{link action=groupperms int=$resource->id _common=1}"><img src="{$smarty.const.ICON_RELATIVE}groupperms.png" border="0" title="Assign group permissions on this Resource" alt="Assign group permissions on this Resource" /></a>
	{/if}
	{/permissions}
	{permissions level=$smarty.const.UILEVEL_NORMAL}
	{if $permissions.edit == 1 || $resource->permissions.edit == 1}
	<a class="mngmntlink resources_mngmntlink" href="{link action=edit id=$resource->id}"><img src="{$smarty.const.ICON_RELATIVE}edit.png" border="0" title="Edit this Resource" alt="Edit this Resource" /></a>
	{/if}
	{if $permissions.delete == 1 || $resource->permissions.delete == 1}
	<a class="mngmntlink resources_mngmntlink" href="{link action=delete id=$resource->id}" onClick="return confirm('Are you sure you want to delete this Resource?');">
		<img src="{$smarty.const.ICON_RELATIVE}delete.png" border="0" title="Delete this Resource" alt="Delete this Resource" />
	</a>
	{/if}
	<br />
	{if $resource->locked != 0 && $resource->flock_owner != $user->id && ($permissions.edit == 1 || $resource->permissions.edit == 1)}
	<i>
	This file is locked by {$resource->lock_owner->firstname} {$resource->lock_owner->lastname}
	{if $user->is_acting_admin != 1}
	You will not be able to edit or update it.
	{/if}
	</i>
	<br />
	{elseif $resource->locked != 0 && $resource->flock_owner == $user->id}
	<i>You have locked this file.  Other users will not be able to update it until you unlock it.</i>
	<br />
	{/if}
	<a class="mngmntlink resources_mngmntlink" href="{$smarty.const.PATH_RELATIVE}{$file->directory}/{$file->filename}">Download</a>
	{if $permissions.edit == 1 || $resource->permissions.edit == 1}
	{if $resource->locked == 0}
	&nbsp;|&nbsp;
	<a class="mngmntlink resources_mngmntlink" href="{link action=updatefile id=$resource->id}">Update File</a>
	&nbsp;|&nbsp;
	<a class="mngmntlink resources_mngmntlink" href="{link action=changelock id=$resource->id}">Lock</a>
	{elseif $resource->flock_owner == $user->id || $user->is_acting_admin == 1}
	&nbsp;|&nbsp;
	<a class="mngmntlink resources_mngmntlink" href="{link action=updatefile id=$resource->id}">Update File</a>
	&nbsp;|&nbsp;
	<a class="mngmntlink resources_mngmntlink" href="{link action=changelock id=$resource->id}">Unlock</a>
	{/if}
	{/if}
	{if $permissions.manage_approval == 1 || $resource->permissions.manage_approval == 1}
		&nbsp;|&nbsp;
		<a class="mngmntlink news_mngmntlink" href="{link module=workflow datatype=resourceitem m=resourcesmodule s=$__loc->src action=revisions_view id=$resource->id}">
			Revisions
		</a>
	{/if}
	{/permissions}
</td></tr>
</table>
<div style="padding-left: 20px;">
{$resource->description}
</div>