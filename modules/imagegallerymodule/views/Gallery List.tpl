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
 *}
 
<div class="imagegallerymodule default">
	{if $moduletitle != ""}<h2>{$moduletitle}</h2>{/if}
	{permissions level=$smarty.const.UILEVEL_NORMAL}
	{if $permissions.create == 1}
	<div class="moduleactions">
		<a class="newgallery" href="{link action=edit_gallery}">{$_TR.new_gallery}</a>
		{br}<a href="{link action=reorder_galleries}"><img src="{$smarty.const.ICON_RELATIVE}manage_images.png" />{$_TR.reorder_galleries}</a>{br}
		{br}
	</div>
	{/if}
	{/permissions}
	<table>
	{foreach from=$galleries item=gallery}
		<tr><td>
			<div class="imagegallery_itemtitle">- <a href="{link action=view_gallery id=$gallery->id}">{$gallery->name}  title="{$gallery->description|strip_tags:false}" </a></div>
		</td><td>
			{include file="`$smarty.const.BASE`modules/imagegallerymodule/views/_edit_delete.tpl"}
		</td></tr>
	{foreachelse}
		<tr><td><div align="center"><i>No Galleries Found</i></div></td></tr>
	{/foreach}
	</table>
</div>