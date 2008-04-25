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
					<a  href="#" onclick="eXp.popImage('{$file->id}',{$file->popwidth},{$file->popheight}); return false;" 
						style="background:url({$file->file->directory}/{$file->thumbnail}) no-repeat; 
						display:block; 
						width:{$gallery->box_size}px;
						height:{$gallery->box_size}px;
						float:left;
						">
					</a>
				{/foreach}
			</div>
			<div style="clear:both"></div>
		</div>
	{foreachelse}
		<div align="center">
			<i>No Galleries Found</i>
		</div>
	{/foreach}

</div>
{script unique="imagepanelpopper" yuimodules='"animation","connection","json","container"'}
{literal}
imagepanel = new YAHOO.widget.Panel("imagepanel", {
											zIndex:90000,
											constraintoviewport:true,
											fixedcenter:true,
											draggable:true,
											modal:true,
											underlay:"shadow",
											close:true,
											visible:false,
											effect:{effect:YAHOO.widget.ContainerEffect.FADE,duration:0.25}
											} );
imagepanel.setHeader('Photo'); 
imagepanel.setBody('&nbsp;');
imagepanel.setFooter('&nbsp;');
imagepanel.render(document.body);

eXp.popImage = function(id,width,height) {
	imagepanel.setBody('<strong>Loading Image...</strong>');
	imagepanel.show();
	YAHOO.util.Connect.asyncRequest('POST', eXp.URL_FULL+'index.php?ajax_action=1&module=imagegallerymodule&action=image_to_panel&id='+id, {
		success : function(o){
			var img = YAHOO.lang.JSON.parse(o.responseText);
			imagepanel.cfg.setProperty("width",width+25+"px");
			imagepanel.cfg.setProperty("height",height+20+"px");
			imagepanel.setHeader(img.name);
			imagepanel.setBody('<img src="'+eXp.URL_FULL+img.file.directory+'/'+img.enlarged+'" height="'+height+'" width="'+width+'" /> ');
			if(img.description.length!=0){
				imagepanel.setFooter('<div id="gallerypopfooter">'+img.description+'</div>');
			} else {
				imagepanel.setFooter('');
			}
			imagepanel.cfg.setProperty("fixedcenter",true);
		},
		failure : function(o){
		},
		timeout : 5000
	});
}

{/literal}	
{/script}

