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
	<a href="{link action=userperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="Assign user permissions on this HTML Template Editor" alt="Assign user permissions on this HTML Template Editor" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="Assign group permissions on this HTML Template Editor" alt="Assign group permissions on this HTML Template Editor" /></a>
	<br />
{/if}
{/permissions}
{if $moduletitle != ""}<div class="moduletitle htmltemplate_moduletitle">{$moduletitle}</div>{/if}
{if $noupload == 1}
<div class="error">
Uploads have been disabled.<br />
{if $uploadError == $smarty.const.SYS_FILES_FOUNDFILE}Found a file in the directory path when creating the directory to store the files in.
{elseif $uploadError == $smarty.const.SYS_FILES_NOTWRITABLE}Unable to create directory to store files in.
{else}An unknown error has occurred.  Please contact the Exponent Developers.
{/if}
</div>
<br />
{else}
Uploads are enabled.<br />
{/if}
{* Association manager currently not properly working
Jump to <a class="mngmntlink htmltemplate_mngmntlink" href="{link action=manage_assocs}">Association Manager</a>
*}
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td class="header htmltemplate_header">Template Name</td>
	<td class="header htmltemplate_header">&nbsp;</td>
</tr>
{foreach from=$templates item=t}
<tr>
	<td>
		<a class="mngmntlink htmltemplate_mngmntlink" href="{link action=view id=$t->id}">
			{$t->title}
		</a>
	</td>
	<td>
		{permissions level=$smarty.const.UILEVEL_NORMAL}
		{if $permissions.edit == 1}
		<a class="mngmntlink htmltemplate_mngmntlink" href="{link action=edit id=$t->id}">
			<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" border="0" title="Edit this HTML Template" alt="Edit this HTML Template" />
		</a>
		{/if}
		{if $permissions.delete == 1}
		<a class="mngmntlink htmltemplate_mngmntlink" href="{link action=delete id=$t->id}" onClick="return confirm('Are you sure you want to delete this HTML Template?');">
			<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" border="0" title="Delete this HTML Template" alt="Delete this HTML Template" />
		</a>
		{/if}
		{/permissions}
	</td>
</tr>
{foreachelse}
<tr>
	<td align="center"><i>No templates found.</i></td>
</tr>
{/foreach}
</table>
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.create == 1}
<hr size="1" />
<a class="mngmntlink htmltemplate_mngmntlink" href="{link action=edit}">Create New Template</a>
&nbsp;&nbsp;
<a class="mngmntlink htmltemplate_mngmntlink" href="{link action=upload}">Upload Template</a>
<br />
{/if}
{/permissions}
