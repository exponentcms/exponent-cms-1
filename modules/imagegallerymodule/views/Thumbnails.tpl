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

<div class="imagegallerymodule thumbnails">
 {if $moduletitle != ""}<h2>{$moduletitle}</h2>{/if}
 {permissions level=$smarty.const.UILEVEL_NORMAL}
  {if $permissions.create == 1}
   <div class="moduleactions">
    <a class="newgallery" href="{link action=edit_gallery}">{$_TR.new_gallery}</a>
    {br}<a href="{link action=reorder_galleries}"><img src="{$smarty.const.ICON_RELATIVE}manage_images.png" alt="" />{$_TR.reorder_galleries}</a>{br}
   </div>
  {/if}
 {/permissions}

 {foreach from=$galleries item=gallery}
  <div class="item">
	<h3><a href="{link action=view_gallery id=$gallery->id view=Slideshow}" title="View {$gallery->name} Slideshow" >{$gallery->name}</a>
		{include file="`$smarty.const.BASE`modules/imagegallerymodule/views/_edit_delete.tpl"}
    </h3>
   {$gallery->description}
   {if $gallery->images !=""}
    <ul class="thumbbox">
     {foreach name=i key="key" from=$gallery->images item=file}
      {if $smarty.foreach.i.iteration <= $gallery->perpage }
      <li>
        <a  href="{$smarty.const.URL_FULL}{$file->file->directory}/{$file->enlarged}" onclick="eXp.popImage('{$file->id}',{$file->popwidth},{$file->popheight}); return false;">
         <img src="{$smarty.const.URL_FULL}{$file->file->directory}/{$file->thumbnail}" alt="{$file->name}" title="{$file->alt}" width="{$file->twidth}px" height="{$file->theight}px" />
        </a>
       {if ($smarty.foreach.i.iteration mod $gallery->perrow) == 0}{br}{/if}
      </li>
      {/if}
	 {foreachelse}	  
		<b><i>No Images in this Gallery</i></b>
     {/foreach}
    </ul>
   {/if}
   {if $gallery->totalpages > 1}
    <div class="paging">
     <span class="previous">
      {if $gallery->currentpage >= 1}<a href="{link action=view_gallery id=$gallery->id page=$gallery->currentpage-1 view=$__view}">&lt; {$_TR.previous}</a>
      {else}&lt; {$_TR.previous}
      {/if}
     </span>
     <span class="next">
      {if $gallery->currentpage != $gallery->totalpages-1}<a href="{link action=view_gallery id=$gallery->id page=$gallery->currentpage+1 view=$__view}">{$_TR.next} &gt;</a>
      {else}{$_TR.next} &gt;
      {/if}
     </span>
    </div>
   {/if}
  </div>
 {foreachelse}
  <p><em>{$_TR.no_galleries}</em></p>
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
  imagepanel.setHeader('{/literal}{$_TR.photo}{literal}'); 
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
