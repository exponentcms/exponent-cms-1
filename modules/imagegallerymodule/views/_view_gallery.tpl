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
{permissions level=$smarty.const.UI_LEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>
	<a href="{link action=groupperms _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
{/if}
{if $permissions.configure == 1}
        	<a href="{link action=configure _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}configure.png" title="{$_TR.alt_configure}" alt="{$_TR.alt_configure}" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}
{assign var=boxw value=$gallery->box_size}
{assign var=boxh value=$gallery->box_size}
{math equation="x+10" x=$gallery->box_size assign=boxtop}
{$gallery->name}
{permissions level=$smarty.const.UI_LEVEL_NORMAL}
{if $permissions.edit == 1}
<a href="{link action=edit_gallery id=$gallery->id}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>
{/if}
{/permissions}
<br />
{$gallery->description}
<hr size="1" />
Page {$currentpage} of {$totalpages}<br />
{$totalimages} image{if $totalimages != 1}s{/if} in gallery.
<br />
{permissions level=$smarty.const.UI_LEVEL_NORMAL}
{if $permissions.manage == 1}
<form method="post" action="">
<input type="hidden" name="module" value="imagegallerymodule" />
<input type="hidden" name="action" value="sort_images" />
<input type="hidden" name="gid" value="{$gallery->id}" />
<select name="sorting">
	<option value="exponent_sorting_byNameAscending">By Name (Ascending)</option>
	<option value="exponent_sorting_byNameDescending">By Name (Descending)</option>
	<option value="exponent_sorting_byPostedAscending">By Creation Date (Ascending)</option>
	<option value="exponent_sorting_byPostedDescending">By Creation Date (Descending)</option>
</select>
<input type="submit" value="Sort Images" />
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
					<img style="border:none;" src="thumb.php?base={$smarty.const.BASE}&amp;file={$image->file->directory}/{$image->file->filename}&amp;height={$boxw}&amp;width={$boxw}&amp;constraint=1" title="{$image->name}" alt="{$image->name}" />
				</a>
				<br />
				<a href="{link action=view_image id=$image->id}">{$image->name}</a>
				{else}
				<a href="#" onClick="window.open('{$image->file->directory}/{$image->file->filename}','image','title=no,status=no,scrollbars=yes'); return false;">
					<img style="border:none;" src="thumb.php?base={$smarty.const.BASE}&amp;file={$image->file->directory}/{$image->file->filename}&amp;height={$boxw}&amp;width={$boxw}&amp;constraint=1" title="{$image->name}" alt="{$image->name}" />
				</a>
				<br />
				<a href="#" onClick="window.open('{$image->file->directory}/{$image->file->filename}','image','title=no,status=no,scrollbars=yes'); return false;">{$image->name}</a>
				{/if}
				<br />
				{permissions level=$smarty.const.UI_LEVEL_NORMAL}
					{if $permissions.manage == 1}
					{if $smarty.foreach.i.first == false}
					{math equation="x-1" x=$image->rank assign=prevrank}
					<a class="mngmntlink imagegallery_mngmntlink" href="{link action=order_images gid=$gallery->id a=$image->rank b=$prevrank}">
						<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}left.png" title="{$_TR.alt_previous}" alt="{$_TR.alt_previous}" />
					</a>
					{/if}
					<a class="mngmntlink imagegallery_mngmntlink" href="{link action=edit_image id=$image->id}">
						<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
					</a>
					<a class="mngmntlink imagegallery_mngmntlink" href="{link action=delete_image id=$image->id}" onClick="return confirm('Are you sure you want to delete this image?');">
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
{if $currentpage != 1}<a href="{link action=view_gallery id=$gallery->id page=$prevpage view=$__view}">&lt; Previous</a>{/if}
{if $currentpage != 1 && $currentpage != $totalpages}&nbsp;&nbsp;|&nbsp;&nbsp;{/if}
{if $currentpage != $totalpages}<a href="{link action=view_gallery id=$gallery->id page=$nextpage view=$__view}">Next &gt;</a>{/if}
{permissions level=$smarty.const.UI_LEVEL_NORMAL}
{if $permissions.manage == 1}
<br />
<br />
<script type="text/javascript">
{literal}
function validate(frm) {
	var num = parseInt(frm.count.value);
	
	if (num <= 0 || isNaN(num)) {
		alert("Please enter only positive, whole numbers.");
		return false;
	}
	
	if (num > 25) num = 25;
	
	frm.count.value = num;
	return true;
}
{/literal}
</script>
<form method="get" onSubmit="return validate(this)">
<input type="hidden" name="module" value="imagegallerymodule" />
<input type="hidden" name="src" value="{$__loc->src}" />
<input type="hidden" name="gid" value="{$gallery->id}" />
<input type="hidden" name="action" value="upload_multiple" />
Upload Multiple files: <input type="text" size="3" name="count" value="3" /><input type="submit" value="Upload" />
</form>
{/if}
{/permissions}
</div>
