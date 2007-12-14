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
 * $Id: Default.tpl,v 1.4 2005/02/23 23:30:27 filetreefrog Exp $
 *}
{*eDebug var=$slides*}
<div class="slideshowmodule yui-based">

{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
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

<script type="text/javascript" src="{$smarty.const.PATH_RELATIVE}modules/slideshowmodule/slideshow.js"></script>
{literal}
<script>
YAHOO.namespace("myProject");
YAHOO.myProject.myModule = function () {

	return  {
		title: [],
		imagepath: [],
		description: [],
		addtitle: function (index,value) {
			this.title[index]=value;
		},
		addimage: function (index,value) {
			this.imagepath[index]=value;
		},
		adddescription: function (index,value) {
			this.description[index]=value;
		}
	};

}(); // the parens here cause the anonymous function to execute and return
</script>
{/literal}

{if $moduletitle != ''}<h1>{$moduletitle}</h1>{/if}
{if $number > 0}
<div id="yui-sldshw-displayer" class="yui-sldshw-displayer">
{foreach key=key name=s from=$slides item=slide}
<img id="frame_fd{$key+1}fade" class="yui-sldshw-{if $smarty.foreach.s.first==true}active{else}cached{/if} yui-sldshw-frame" src="{$smarty.const.URL_FULL}{$slide->file->directory}/{$slide->file->filename}" />


<script type="text/javascript">
YAHOO.myProject.myModule.addtitle("{$key}","{$slide->name}");
YAHOO.myProject.myModule.addimage("{$key}","{$smarty.const.URL_FULL}{$slide->file->directory}/{$slide->file->filename}");
YAHOO.myProject.myModule.adddescription("{$key}","{$slide->description}");
//YAHOO.popinfo.addtitle("{$key}","{$slide->description}");
//YAHOO.popinfo.popupinfoname[{$key}] = "{$slide->description}";
</script>

{/foreach}
</div>

<div id="slideshowmask">
	&nbsp;
</div>



{literal}
<script type="text/javascript">
YAHOO.util.Event.onDOMReady(function() { slideshow = new YAHOO.myowndb.slideshow("yui-sldshw-displayer", {effect:  YAHOO.myowndb.slideshow.effects.fadeOut});
slideshow.loop();
slideshow.handlepause("slideshowmask");


var slideshowpop = new YAHOO.widget.Panel("slideshowbaloon", { visible:false, draggable:false, close:false, zIndex:15, xy:[0,0] } );
slideshowpop.setHeader("");
slideshowpop.setBody("");
slideshowpop.setFooter("");
slideshowpop.render("body");

function swapinfo(){
	//alert(YAHOO.util.Region.getRegion('doc').left);
	YAHOO.util.Dom.setStyle("slideshowbaloon","margin-top",YAHOO.util.Region.getRegion('slideshowmask').top-300+'px');
	var activeframe = slideshow.get_frame_index(slideshow.get_active_frame());
	slideshowpop.setHeader(YAHOO.myProject.myModule.title[activeframe]);
	var picture = '<div class="theimage" style="background:url('+YAHOO.myProject.myModule.imagepath[activeframe]+')"><div class="popframe">&nbsp; </div> </div>';
	//alert(picture);
	slideshowpop.setBody(picture);
	var desc = YAHOO.myProject.myModule.description[activeframe];
	//alert(activeframe);
	slideshowpop.setFooter(desc ? desc : "");
	slideshowpop.show();
}

YAHOO.util.Event.addListener("slideshowmask", "mouseover", swapinfo, slideshowpop, true);
YAHOO.util.Event.addListener("slideshowmask", "mouseout", slideshowpop.hide, slideshowpop, true);

});





</script>
{/literal}	

{else}
There are no slides in the slideshow.<br />
{/if}


{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.create_slide == 1 || $permissions.edit_slide == 1 || $permissions.delete_slide == 1}
<a class="mngmntlink slideshow_mngmntlink" href="{link action=manage_slides}">Manage Slides</a>
{/if}
{/permissions}

{*
<script type="text/javascript" charset="utf-8">
YAHOO.util.Event.onDOMReady(function(){ldelim}

	slideshow = new YAHOO.myowndb.slideshow("yui-sldshw-displayer",
				{ldelim} frames: [ 
					{foreach key=key name=s from=$slides item=slide}
					{ldelim} type: 'image_url', value: '{$slide->file->directory}/{$slide->file->filename}', id: "img{$key}"{rdelim}{if $smarty.foreach.s.last!=true},{/if}
					{/foreach}
					],
				  effect: YAHOO.myowndb.slideshow.effects.fadeIn,

				);
				
	slideshow.loop();
{rdelim});			
</script>
*}
</div>


