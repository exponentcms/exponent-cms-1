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
 * $Id: Gallery\040List.tpl,v 1.3 2005/02/24 20:14:35 filetreefrog Exp $
 *}


<div class="imagegallerymodule default">

	{include file="`$smarty.const.BASE`modules/common/views/_permission_icons.tpl"}

	{if $moduletitle != ""}<h1>{$moduletitle}</h1>{/if}
	
	{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
	{if $permissions.create == 1}
		<div class="moduleactions">
				<a class="creategallery" href="{link action=edit_gallery}">
					Create New Gallery
				</a>
		</div>
	{/if}
	{/permissions}
	
		{foreach key="key" name="gallery" from=$galleries item=gallery}
		<div class="item">
			<h2><a href="{link action=view_gallery id=$gallery->id}">{$gallery->name}</a></h2>
			{permissions level=$smarty.const.UILEVEL_NORMAL}
			<div class="itemactions">
				{include file="`$smarty.const.BASE`modules/imagegallerymodule/views/_edit_delete.tpl"}
			</div>
			{/permissions}
			
			{if $gallery->description}
			<div class="bodycopy">
				{if $gallery->images[0]->thumbnail}
					<a href="{link action=view_gallery id=$gallery->id}">
						<img class="firstimage" align="left" hspace="5px" src="{$smarty.const.URL_FULL}thumb.php?file={$gallery->images[0]->file->directory}/{$gallery->images[0]->thumbnail}&amp;constraint=1&amp;width=75&amp;height=1000">
					</a>
				{/if}
				{$gallery->description}
			</div>
			{/if}
		</div>
		{foreachelse}
			<div align="center"><i>No Galleries Found</i></div>
		{/foreach}
	
	
</div>
