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

<div class="slideshowmodule manage">


	{include file="`$smarty.const.BASE`modules/common/views/_permission_icons.tpl"}	
{if $moduletitle != ''}<h1>{$moduletitle}</h1>{/if}

{foreach from=$slides item=slide}
<div class="item {cycle values="odd,even"}">
		<div class="description">
			{$slide->description}
		</div>
		<img src="{$smarty.const.PATH_RELATIVE}thumb.php?base={$smarty.const.BASE}&amp;file={$slide->file->directory}/{$slide->file->filename}&amp;constraint=1&amp;width=150px&amp;height=200" />
		<br />{$slide->name}
		
		{permissions level=$smarty.const.UILEVEL_NORMAL}
			<div class="itemactions">
				{if $permissions.edit_slide == 1}
				<a class="mngmntlink slideshow_mngmntlink" href="{link action=edit_slide id=$slide->id}">
					<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
				</a>
				{/if}
				{if $permissions.delete_slide == 1}
				<a class="mngmntlink slideshow_mngmntlink" href="{link action=delete_slide id=$slide->id}">
					<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
				</a>
				{/if}
			</div>		
		{/permissions}
	
</div>
{foreachelse}
<i>No slides were found</i>
{/foreach}
<div class="moduleactions">
	{permissions level=$smarty.const.UILEVEL_NORMAL}
	{if $permissions.create_slide == 1}
	<a class="mngmntlink slideshow_mngmntlink" href="{link action=edit_slide}">
		Create Slide
	</a>
</div>
{/if}
{/permissions}	


</div>
