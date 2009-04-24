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
* $Id: Default.tpl,v 1.4 2009/02/13 20:14:35 maiagood Exp $
*}
<div class="imagegallerymodule thumbnails">
 {include file="`$smarty.const.BASE`modules/common/views/_permission_icons.tpl"}	

 {if $moduletitle != ""}<h1>{$moduletitle}</h1>{/if}

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
   {if $gallery->name !=""}<h2>{$gallery->name}</h2>{/if}
   {permissions level=$smarty.const.UILEVEL_NORMAL}
    <div class="itemactions">
     {permissions level=$smarty.const.UILEVEL_NORMAL}
      {if $permissions.edit == 1}
       <a href="{link action=view_gallery id=$gallery->id}"><img src="{$smarty.const.ICON_RELATIVE}manage_images.png" />{$_TR.add_reorder_images}</a>{br}
      {/if}
      {if $permissions.edit == 1}
       <a class="mngmntlink imagegallery_mngmntlink" href="{link action=edit_gallery id=$gallery->id}">
        <img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />{$_TR.edit_gallery}
       </a>{br}
      {/if}
      {if $permissions.delete == 1}
       <a class="mngmntlink imagegallery_mngmntlink" href="{link action=delete_gallery id=$gallery->id}">
        <img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />{$_TR.delete_gallery}
       </a>
      {/if}
     {/permissions}
    </div>
   {/permissions}
   {$gallery->description}
   {if $gallery->images !=""}
    <ul class="thumbbox">
     {foreach name=i key="key" from=$gallery->images item=file}
      {if $smarty.foreach.i.iteration <= $gallery->perpage }
      <li>
        <a  href="{$smarty.const.URL_FULL}{$file->file->directory}/{$file->enlarged}" onclick="eXp.popImage('{$file->id}',{$file->popwidth},{$file->popheight}); return false;">
         <img src="{$smarty.const.URL_FULL}{$file->file->directory}/{$file->thumbnail}" alt="{$file->name}" width="{$file->twidth}px" height="{$file->theight}px" />
        </a>
       {if ($smarty.foreach.i.iteration mod $gallery->perrow) == 0}{br}{/if}
      </li>
      {/if}
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

