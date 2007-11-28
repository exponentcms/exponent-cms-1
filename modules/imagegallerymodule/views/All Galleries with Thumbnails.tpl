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
<div id="gallery-images" class="imagegallerymodule all-galleries-with-thumbnails">
	{include file="_permissions.tpl"}

	{if $moduletitle != ""}<h1>{$moduletitle}</h1>{/if}
	{foreach from=$galleries item=gallery}
		<div class="item">
			<h1>{$gallery->name}</h1>
			{include file="_edit_delete.tpl"}
			<p>{$gallery->description}</p>
			<div class="thumbbox">
				{foreach from=$gallery->images item=file}
					<a href="javascript:void(0);" onclick="popImage('{$file->name}', '{$file->file->directory}/{$file->file->filename}')">
						<img src="thumb.php?file={$file->file->directory}/{$file->file->filename}&square=100" />
					</a>
				{/foreach}
			</div>
		</div>
	{foreachelse}
		<div align="center"><i>No Galleries Found</i></div>
	{/foreach}

	{permissions level=$smarty.const.UILEVEL_NORMAL}
		{if $permissions.create == 1}
			<a class="mngmntlink imagegallery_mngmntlink" href="{link action=edit_gallery}">New Gallery</a>
		{/if}
		{if $permissions.edit == 1}
			<a href="{link action=view_gallery id=$gallery->id}">Edit {$gallery->name}</a>
		{/if}
	{/permissions}
</div>

{literal}
<script type="text/javascript">
	YAHOO.util.Event.onAvailable("gallery-images", function () {
			myPanel = new YAHOO.widget.Panel("myPanel", {width:"400px",fixedcenter: true,constraintoviewport: true,underlay:"shadow",close:true,visible:false,draggable:true} );
			myPanel.setHeader('asdfasdf');
                myPanel.setBody('asdfas');
			myPanel.render('custom-doc');
		}
	);

	function popImage(imgname, imgfile) {
		myPanel.setHeader(imgname);
		myPanel.setBody('<img src="thumb.php?file=' + imgfile + '&constraint=1&width=400&height=400" />');
		myPanel.show();
		console.debug(myPanel);
	}	
</script>
{/literal}
