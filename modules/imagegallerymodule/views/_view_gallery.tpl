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
 
<div class="imagegallerymodule view-gallery">
	{assign var=boxw value=$gallery->box_size}
	{assign var=boxh value=$gallery->box_size}
	{math equation="x+10" x=$gallery->box_size assign=boxtop}
	{$gallery->name}
	{permissions level=$smarty.const.UI_LEVEL_NORMAL}
		{if $permissions.edit == 1}
			<a href="{link action=edit_gallery id=$gallery->id}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>
		{/if}
	{/permissions}
	{br}
	{$gallery->description}
	<hr size="1" />
	{$_TR.page_of|sprintf:$currentpage:$totalpages}{br}
	{if $totalimages != 1}
		{assign var=image_word value=$_TR.image_plural}
	{else}
		{assign var=image_word value=$_TR.image_singular}
	{/if}
	{$totalimages} {$_TR.image_in_gallery|sprintf:$image_word}
	{br}
	{permissions level=$smarty.const.UI_LEVEL_NORMAL}
		{if $permissions.manage == 1}
			<form method="post" action="">
				<input type="hidden" name="module" value="imagegallerymodule" />
				<input type="hidden" name="action" value="sort_images" />
				<input type="hidden" name="gid" value="{$gallery->id}" />
				<select name="sorting">
					<option value="exponent_sorting_byNameAscending">{$_TR.by_name} ({$_TR.ascending})</option>
					<option value="exponent_sorting_byNameDescending">{$_TR.by_name} ({$_TR.descending})</option>
					<option value="exponent_sorting_byPostedAscending">{$_TR.by_creation_date} ({$_TR.ascending})</option>
					<option value="exponent_sorting_byPostedDescending">{$_TR.by_creation_date} ({$_TR.descending})</option>
				</select>
				<input type="submit" value="{$_TR.sort_images}" />
			</form>
		{/if}
	{/permissions}
	<table cellspacing="0" cellpadding="0" border="0">
		{foreach from=$table item=row}
			<tr>
				{foreach from=$row item=image}
					<td valign="bottom" align="center" style="padding: 1em">
						{if $image->newwindow == 0}
							<a href="{link action=view_image id=$image->id}">
								<img style="border:none;" src="thumb.php?base={$smarty.const.BASE}&amp;file={$image->file->directory}/{$image->file->filename}&amp;height={$boxw}&amp;width={$boxw}&amp;constraint=1" title="{$image->alt}" alt="{$image->alt}" />
							</a>
							<br />
							<a href="{link action=view_image id=$image->id}">{$image->name}</a>
						{else}
							<a href="#" onclick="window.open('{$image->file->directory}/{$image->file->filename}','image','title=no,status=no,scrollbars=yes'); return false;">
								<img style="border:none;" src="thumb.php?base={$smarty.const.BASE}&amp;file={$image->file->directory}/{$image->file->filename}&amp;height={$boxw}&amp;width={$boxw}&amp;constraint=1" title="{$image->alt}" alt="{$image->alt}" />
							</a>
							<br />
							<a href="#" onclick="window.open('{$image->file->directory}/{$image->file->filename}','image','title=no,status=no,scrollbars=yes'); return false;">{$image->name}</a>
						{/if}
						<br />
						{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
							{if $permissions.manage == 1}
								{if $smarty.foreach.i.first == false}
									{math equation="x-1" x=$image->rank assign=prevrank}
									<a class="mngmntlink imagegallery_mngmntlink" href="{link action=order_images gid=$gallery->id a=$image->rank b=$prevrank}">
										<img class="mngmnt_icon" style="border:none;" text-align:center; margin-right: 5px;" src="{$smarty.const.ICON_RELATIVE}left.png" title="{$_TR.previous}" alt="{$_TR.previous}" />
									</a>
								{/if}
								<a class="mngmntlink imagegallery_mngmntlink" href="{link action=edit_image id=$image->id}">
									<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
								</a>
								<a class="mngmntlink imagegallery_mngmntlink" href="{link action=delete_image id=$image->id}" onclick="return confirm('{$_TR.alt_delete_confirm}');">
									<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
								</a>
								{if $smarty.foreach.i.last == false}
									{math equation="x+1" x=$image->rank assign=nextrank}
									<a class="mngmntlink imagegallery_mngmntlink" href="{link action=order_images gid=$gallery->id a=$image->rank b=$nextrank}">
										<img class="mngmnt_icon" style="border:none; text-align:center; margin-right: 5px;" src="{$smarty.const.ICON_RELATIVE}right.png" title="{$_TR.next}" alt="{$_TR.next}" />
									</a>
								{/if}
								<br />
							{/if}
						{/permissions}
					</td>
				{/foreach}
			</tr>
		{/foreach}
	</table>
	<div style="clear: both; border-top: 2px dashed lightgrey">
	{if $gallery->currentpage != 1}<a href="{link action=view_gallery id=$gallery->id page=$gallery->prevpage view=$__view}">&lt; {$_TR.previous}</a>{/if}
	{if $gallery->currentpage != 1 && $gallery->currentpage != $gallery->totalpages}&nbsp;&nbsp;|&nbsp;&nbsp;{/if}
	{if $gallery->currentpage != $gallery->totalpages}<a href="{link action=view_gallery id=$gallery->id page=$gallery->nextpage view=$__view}">{$_TR.next} &gt;</a>{/if}
	{permissions level=$smarty.const.UI_LEVEL_NORMAL}
		{if $permissions.manage == 1}
			{br}
			{br}
			<script type="text/javascript">
				{literal}
					function validate(frm) {
						var num = parseInt(frm.count.value);
						
						if (num <= 0 || isNaN(num)) {
							alert({/literal}{$_TR.whole_number}{literal});
							return false;
						}
						
						if (num > 25) num = 25;
						
						frm.count.value = num;
						return true;
					}
				{/literal}
			</script>
			<form method="POST" onsubmit="return validate(this)" action="{$smarty.const.URL_FULL}index.php">
				<input type="hidden" name="module" value="imagegallerymodule" />
				<input type="hidden" name="src" value="{$__loc->src}" />
				<input type="hidden" name="gid" value="{$gallery->id}" />
				<input type="hidden" name="action" value="upload_multiple" />
				{$_TR.upload_files} <input type="text" size="3" name="count" value="3" /><input type="submit" value="{$_TR.upload}" />
			</form>
		{/if}
	{/permissions}
	</div>
</div>
