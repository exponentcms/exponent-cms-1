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
 * $Id: _view_gallery.tpl,v 1.11 2005/06/22 22:15:23 filetreefrog Exp $
 *}
 
<div class="imagegallerymodule view-all-galleries">
	{foreach from=$galleries item=gallery}
		{assign var=boxw value=$gallery->box_size}
		{assign var=boxh value=$gallery->box_size}
		{math equation="x+10" x=$gallery->box_size assign=boxtop}
		{math equation="x+60" x=$gallery->box_size assign=boundingbox}
	
		<h1>{$gallery->name}</h1>
		{br}
		{permissions level=$smarty.const.UILEVEL_NORMAL}
			{if $permissions.manage == 1}
				<script type="text/javascript">
				{literal}
				function validate(frm) {
					var num = parseInt(frm.count.value);

					if (num <= 0 || isNaN(num)) {
						alert("{/literal}{$_TR.whole_number}{literal}");
						return false;
					}

					if (num > 25) num = 25;

					frm.count.value = num;
					return true;
				}
				{/literal}
				</script>
				<form method="get" onsubmit="return validate(this)" action="{$smarty.const.URL_FULL}index.php">
				<input type="hidden" name="module" value="imagegallerymodule" />
				<input type="hidden" name="src" value="{$__loc->src}" />
				<input type="hidden" name="gid" value="{$gallery->id}" />
				<input type="hidden" name="action" value="upload_multiple" />
				{$_TR.upload_files} {$galleries[0]->name}: <input type="text" size="3" name="count" value="3" /><input type="submit" value="{$_TR.upload}" />
				</form>
			{/if}
		{/permissions}

		{br}
		{permissions level=$smarty.const.UI_LEVEL_NORMAL}
			{if $permissions.manage != 1}
				<ul>
					{foreach name=a from=$gallery->images item=image}
						<li class="imagegallery_picbox">
							<a href="{link action=view_image id=$image->id}">
								<img src="{$smarty.const.PATH_RELATIVE}{$image->file->directory}/{$image->thumbnail}" alt="{$image->name}" title="{$image->name}" />{$image->name}
							</a>
							{if $smarty.foreach.a.iteration mod $gallery->perrow == 0}{br}{/if}			
						</li>
					{/foreach}
				</ul>
			{else}
	<table>
	<tr>
		{foreach name=a from=$gallery->images item=image}
			<td class="imagegallery_picbox">
					<a href="{link action=view_image id=$image->id}">
						<img src="{$smarty.const.PATH_RELATIVE}{$image->file->directory}/{$image->thumbnail}" alt="{$image->alt}" title="{$image->alt}" />
					</a>
					<div>
						<a href="{link action=view_image id=$image->id}">{$image->name}</a>
					</div>
					<table>
					{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
					{if $permissions.manage == 1}
					<tr>
					{if $smarty.foreach.a.first == false}
					{math equation="x-1" x=$image->rank assign=prevrank}
					<td>
						<a class="mngmntlink imagegallery_mngmntlink" href="{link action=order_images gid=$gallery->id a=$image->rank b=$prevrank}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}left.png" title="{$_TR.previous}" alt="{$_TR.previous}" /></a>
					</td>
					{/if}
					<td>
						<a class="mngmntlink imagegallery_mngmntlink" href="{link action=edit_image id=$image->id}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>
					</td>
					<td>
						<a class="mngmntlink imagegallery_mngmntlink" href="{link action=delete_image id=$image->id}" onclick="return confirm('{$_TR.conf_del_img}');"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" /></a>
					</td>
					{if $smarty.foreach.a.last == false}
					{math equation="x+1" x=$image->rank assign=nextrank}
					<td>
					<a class="mngmntlink imagegallery_mngmntlink" href="{link action=order_images gid=$gallery->id a=$image->rank b=$nextrank}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}right.png" title="{$_TR.next}" alt="{$_TR.next}" /></a>
					</td>
					{/if}
					</tr>
					{/if}
					{/permissions}
					</table>
			</td>
			{if $smarty.foreach.a.iteration mod $gallery->perrow == 0}</tr>{/if}			
		{/foreach}
		</tr>
		</table>
			{/if}
		{/permissions}
	{foreachelse}
		<p><em>{$_TR.no_galleries}</em></p>
	{/foreach}
</div>
