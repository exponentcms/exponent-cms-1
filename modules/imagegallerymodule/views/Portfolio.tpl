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
 * $Id: Default.tpl,v 1.4 2005/02/24 20:14:35 filetreefrog Exp $
 *}

<div class="imagegallerymodule portfolio">
	{include file="_permissions.tpl"}

	{if $moduletitle != ""}<h1>{$moduletitle}</h1>{/if}
	{foreach from=$galleries item=gallery}
		<div class="item">
			<h1>{$gallery->name}</h1>
			<!--h1><a href="{link action=view_gallery id=$gallery->id}">{$gallery->name}</a></h1-->
			{include file="_edit_delete.tpl"}
			<p>{$gallery->description}</p>
			{foreach from=$gallery->images item=file}
				<div class="image">
					<img alt="{$file->alt}" src="thumb.php?file={$file->file->directory}/{$file->file->filename}&constraint=1&width=400&height=270" />
					<br />
					<div class="text">{$file->description}</div>
				</div>
			{/foreach}
		</div>
	{foreachelse}
		<div align="center"><i>No Galleries Found</i></div>
	{/foreach}

	{permissions level=$smarty.const.UILEVEL_NORMAL}
		{if $permissions.create == 1}
			<a class="mngmntlink imagegallery_mngmntlink" href="{link action=edit_gallery}">New Gallery</a>
		{/if}
		<br />
		{if $permissions.edit == 1}
                        <a href="{link action=view_gallery id=$gallery->id}">Edit {$gallery->name}</a>
                {/if}
	{/permissions}
</div>
