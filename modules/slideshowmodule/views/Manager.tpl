{*
 *
 * Copyright (c) 2004-2005 OIC Group, Inc.
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
 * $Id: Manager.tpl,v 1.4 2005/02/23 23:30:27 filetreefrog Exp $
 *}
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>
	<a href="{link action=groupperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
{/if}
{if $permissions.configure == 1}
        	<a href="{link action=configure _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}configure.png" title="{$_TR.alt_configure}" alt="{$_TR.alt_configure}" /></a>\n<img border="0" src="{$smarty.const.ICON_RELATIVE}configure.gif" title="{$_TR.alt_configure}" alt="{$_TR.alt_configure}" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}
{if $moduletitle != ''}<div class="moduletitle">{$moduletitle}</div>{/if}
<table cellpadding="0" cellspacing="0" border="0" width="100%">
{foreach from=$slides item=slide}
<tr>
	<td>
		<img src="{$smarty.const.PATH_RELATIVE}thumb.php?base={$smarty.const.BASE}&file={$slide->file->directory}/{$slide->file->filename}&scale={$slide->scale}" />
		<br />{$slide->name}
	</td>
	<td>
		{permissions level=$smarty.const.UILEVEL_NORMAL}
		{if $permissions.edit_slide == 1}
		<a class="mngmntlink slideshow_mngmntlink" href="{link action=edit_slide id=$slide->id}">
			<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" border="0" />
		</a>
		{/if}
		{if $permissions.delete_slide == 1}
		<a class="mngmntlink slideshow_mngmntlink" href="{link action=delete_slide id=$slide->id}">
			<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" border="0" />
		</a>
		{/if}
		{/permissions}
	</td>
</tr>
{foreachelse}
<tr>
	<td><i>No slides were found</i></td>
</tr>
{/foreach}
</table>
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.create_slide == 1}
<a class="mngmntlink slideshow_mngmntlink" href="{link action=edit_slide}">
	Create Slide
</a>
{/if}
{/permissions}