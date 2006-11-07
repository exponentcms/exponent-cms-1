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
	<a href="{link action=userperms _common=1}" title="Assign permissions on this Module"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}" title="Assign group permissions on this Module"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" /></a>
	<a href="{link action=configure _common=1}" title="Configure this Module"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}configure.png" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}

{foreach from=$galleries item=gallery}
{assign var=boxw value=$gallery->box_size}
{assign var=boxh value=$gallery->box_size}
{math equation="x+10" x=$gallery->box_size assign=boxtop}
{math equation="x+60" x=$gallery->box_size assign=boundingbox}
<div class="imagegallery_gallerytitle">
	{$gallery->name}&nbsp;&nbsp;
        {permissions level=$smarty.const.UI_LEVEL_NORMAL}
        {if $permissions.edit == 1}
        	<a href="{link action=view_gallery id=$gallery->id}" title="Manage Images"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}manage_images.png" /></a>
        	<a href="{link action=edit_gallery id=$gallery->id}" title="Edit Gallery"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}edit.png" /></a>
      	{/if}
        {/permissions}
</div>
<table cellspacing="0" cellpadding="0" border="0" width="100%" align="center" style="margin-bottom: 15px;">
<tr>
	{foreach name=a from=$gallery->images item=image}
		<td valign="bottom" align="center" class="imagegallery_picbox">
			<div style="width: {$boxtop}; height:{$boundingbox};">
			<div class="imagegallery_picbox" style="width: {$boxtop}; height:{$boxtop}; vertical-align: middle;">
				<a href="{link action=view_image id=$image->id}">
					<img border="0" src="{$image->file->directory}/{$image->thumbnail}" alt="{$image->name}" title="{$image->name}" />
				<span>
					<div>
						<img border="0" align="center" src="{$image->file->directory}/{$image->file->filename}" height=240 width=320 />
					</div>
					<div>
						{$image->description}
					</div>
				</span>
				</a>
				<div>
				<a href="{link action=view_image id=$image->id}">{$image->name}</a>
				</div>
				<table cellpadding="0" cellspacing="0" border="0">
				<tr>
				{permissions level=$smarty.const.UI_LEVEL_NORMAL}
				{if $permissions.manage == 1}
				{if $smarty.foreach.a.first == false}
				{math equation="x-1" x=$image->rank assign=prevrank}
				<td width="16">
					<a class="mngmntlink imagegallery_mngmntlink" href="{link action=order_images gid=$gallery->id a=$image->rank b=$prevrank}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}left.png" /></a>
				</td>
				{/if}
				<td width="16">
				<a class="mngmntlink imagegallery_mngmntlink" href="{link action=edit_image id=$image->id}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}edit.png" /></a>
				</td>
				<td width="16">
				<a class="mngmntlink imagegallery_mngmntlink" href="{link action=delete_image id=$image->id}" onClick="return confirm('Are you sure you want to delete this image?');"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.png" /></a>
				</td>
				{if $smarty.foreach.a.last == false}
				{math equation="x+1" x=$image->rank assign=nextrank}
				<td width="16">
				<a class="mngmntlink imagegallery_mngmntlink" href="{link action=order_images gid=$gallery->id a=$image->rank b=$nextrank}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}right.png" /></a>
				</td>
				{/if}
				{/if}
				{/permissions}
				<tr>
				</table>
			</div>
			</div>
		</td>
		{if $smarty.foreach.a.iteration mod $gallery->perrow == 0}</tr>{/if}			
	{/foreach}
	</tr>
	</table>
	{permissions level=$smarty.const.UILEVEL_NORMAL}
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
			Upload Multiple files to {$gallery->name}: <input type="text" size="3" name="count" value="3" /><input type="submit" value="Upload" />
			</form>
		{/if}
		{/permissions}
{foreachelse}
<div align="center"><i>No Galleries Found</i></div>
{/foreach}
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.create == 1}
        <a class="mngmntlink imagegallery_mngmntlink" href="{link action=edit_gallery}">
                New Gallery
        </a>
{/if}
{/permissions}

