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


<div class="imagegallerymodule all-galleries-with-thumbnails">

	{include file="`$smarty.const.BASE`modules/common/views/_permission_icons.tpl"}	

	{if $moduletitle != ""}<h1>{$moduletitle}</h1>{/if}
	
	{permissions level=$smarty.const.UILEVEL_NORMAL}
	{if $permissions.create == 1}
	<div class="moduleactions">
		<a class="newgallery" href="{link action=edit_gallery}">New Gallery</a><br />
	</div>
	{/if}
	{/permissions}

	{foreach from=$galleries item=gallery}
		<div class="item">
			<h2>{$gallery->name}</h2>
			{permissions level=$smarty.const.UILEVEL_NORMAL}
			<div class="itemactions">
				{include file="_edit_delete.tpl"}
			</div>
			{/permissions}
			<p>{$gallery->description}</p>
			<div class="thumbbox">
				{foreach key="key" from=$gallery->images item=file}
					<a href="#" onclick="YAHOO.exp.popImage('{$file->name|escape:'quotes'}','{$file->description|escape:'quotes'}','{$file->file->directory}/{$file->enlarged}',{$file->popwidth},{$file->popheight}); return false" 
						style="background:url({$file->file->directory}/{$file->thumbnail}) no-repeat; 
						display:block; 
						width:{$gallery->box_size}px;
						height:{$gallery->box_size}px;
						float:left;
						">
					</a>
				{/foreach}
			</div>
		</div>
	{foreachelse}
		<div align="center">
			<i>No Galleries Found</i>
		</div>
	{/foreach}

</div>

