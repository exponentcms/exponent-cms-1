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
	{if $moduletitle != ""}<h2>{$moduletitle}</h2>{/if}
	{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
	{if $permissions.create == 1}
		<div class="moduleactions">
			<a class="creategallery mngmntlink" href="{link action=edit_gallery}">{$_TR.new_gallery}</a>
			{br}<a class="mngmntlink" href="{link action=reorder_galleries}"><img src="{$smarty.const.ICON_RELATIVE}manage_images.png" alt="" />{$_TR.reorder_galleries}</a>{br}
		</div>
	{/if}
	{/permissions}
	<table>	
		{foreach key="key" name="gallery" from=$galleries item=gallery}
			{assign var=boxw value=$gallery->box_size}
			{assign var=boxh value=$gallery->box_size}
			<tr>
				<div class="item">
					<td>
					{if $gallery->images[0]->thumbnail}
						<a href="{link action=view_gallery id=$gallery->id}">
							<img class="firstimage" src="{$smarty.const.URL_FULL}thumb.php?file={$gallery->images[0]->file->directory}/{$gallery->images[0]->thumbnail}&amp;height={$boxw}&amp;width={$boxw}&amp;constraint=1" alt="{if $gallery->images[0]->name !=""}{$gallery->images[0]->name}{else}{$_TR.first_image}{/if}" title="{if $gallery->images[0]->name !=""}{$gallery->images[0]->name}{else}{$_TR.first_image}{/if}" />
						</a>
					{/if}
					</td>
					<td>
						<h3>
							<a href="{link action=view_gallery id=$gallery->id}"  title="{$gallery->description|strip_tags:false}" >{$gallery->name}</a>
							{include file="`$smarty.const.BASE`modules/imagegallerymodule/views/_edit_delete.tpl"}
						</h3>
						{if $gallery->description}
							<div class="bodycopy">
								{$gallery->description}
							</div>
						{/if}
					</td>
				</div>
			</tr>
		{foreachelse}
			<tr><p><em>{$_TR.no_galleries}</em></p></tr>
		{/foreach}
	</table>
</div>
