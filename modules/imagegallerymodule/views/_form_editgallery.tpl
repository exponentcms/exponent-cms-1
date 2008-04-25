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
 * $Id: _form_editgallery.tpl,v 1.3 2005/02/19 16:40:42 filetreefrog Exp $
 *}
<div class="imagegallery edit">
	<div class="form_header">
		<h1>{if $is_edit}Edit Gallery Properties{else}New Gallery{/if}</h1>
		<p>Enter the name and description of the gallery below.</p>
	</div>
	{form name="gallery" module="imagegallerymodule" int=2 action="save_gallery"}
	{control type="hidden" name="id" value=$gallery->id}
	{control type="textbox" name="name" value=$gallery->name label="Name"}
	{control type="editor" name="description" value=$gallery->description label="Description"}
	{control type="textbox" name="box_size" value=$gallery->box_size label="Thumbnail Size (pixels)"}
	{control type="textbox" name="pop_size" value=$gallery->pop_size label="Enlarged Size (pixels)"}
	{control type="textbox" name="perrow" value=$gallery->perrow label="Images per row"}
	{control type="textbox" name="perpage" value=$gallery->perpage label="Images per page"}
	{control type="buttongroup" submit="Save Gallery Settings" cancel="Cancel"}
	{/form}	

</div>

{script yuimodules='"container","json","connection"' unique="saveimagegallery"}
{literal}
// Initialize the temporary Panel to display while waiting for external content to load
YAHOO.util.Event.on("gallery","submit",function(e){
	YAHOO.util.Event.stopEvent(e);
	YAHOO.util.Dom.get('description').value = FCKeditorAPI.GetInstance('description').GetXHTML();
	YAHOO.util.Dom.get('Submit').value = "Saving...";
	YAHOO.util.Dom.get('Submit').enabled = false;
	var postvars = YAHOO.util.Connect.setForm("gallery");
	var wait = 
			new YAHOO.widget.Panel("wait",	
				{ width:"300px", 
				  fixedcenter:true, 
				  close:false, 
				  draggable:false, 
				  zindex:9999,
				  modal:true,
				  visible:false
				} 
			);
			//alert(postvars)
	wait.setHeader("Rebuilding thumbnail and popup images");
	wait.setBody('<div id="messagebox">Saving gallery settings...</div><img src="http://us.i1.yimg.com/us.yimg.com/i/us/per/gr/gp/rel_interstitial_loading.gif" />');
	wait.render(document.body);
	wait.show();

	var messagebox = YAHOO.util.Dom.get("messagebox");

	var sUrl = eXp.URL_FULL+"index.php?ajax_action=1";
	YAHOO.util.Connect.asyncRequest('POST', sUrl, 
	{
		success : function (o){
			if(o.responseText == "no-resize"){
				messagebox.innerHTML = "<h4>Complete!</h4>";
				setTimeout(function(){ wait.hide();window.location = eXp.URL_FULL+"index.php?ajax_action=1&action=ajax_flow_redirect&module=common";},"1000");
			}else{
				updateImages(o);
			}
		},
		failure : function(o){
		},
		timeout : 50000
	});

	function updateImages (o) {
		var gallery = YAHOO.lang.JSON.parse(o.responseText);
		//alert(gallery.images.length);
		//for (i=0; i<=gallery.images.length; i++){
		var i=0;
		recursiveUpdate(gallery.images[0],i);
		function recursiveUpdate(img,i){
			messagebox.innerHTML = "<span style='color:#000'>Updating "+ i + " of " + gallery.images.length + " images</span>";
			var iUrl = eXp.URL_FULL+"index.php?ajax_action=1&module=imagegallerymodule&action=rebuild_images";
			YAHOO.util.Connect.asyncRequest('POST', iUrl, 
			{
				success : function (o){
					//console.debug(o.responseText);
					if(i < gallery.images.length){
						i++;
						recursiveUpdate(gallery.images[i],i)
					} else {
						messagebox.innerHTML = "<h4>Complete!</h4>";
						setTimeout(function(){ wait.hide();window.location = eXp.URL_FULL+"index.php?ajax_action=1&action=ajax_flow_redirect&module=common";},"1000");
					}
				},
				failure : function(o){
				},
				timeout : 50000
			},"galobject="+YAHOO.lang.JSON.stringify(img));			
		}
		//}

	}



});
{/literal}

{/script}




