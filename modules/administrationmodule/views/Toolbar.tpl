{*
 * Copyright (c) 2004-2006 OIC Group, Inc.
 * Written and Designed by James Hunt
 *
 * This file is part of Exponent
 *
 * Exponent is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * GPL: http://www.gnu.org/licenses/gpl.txt
 *
 *}
{literal}
<style>
	#overlay1.yui-overlay { position:absolute;z-index:9999;background:#fff;border:1px dotted black;padding:0px;margin:10px; }
	#overlay1.yui-overlay .hd { border:1px solid red;padding:5px; }
	#overlay1.yui-overlay .bd { border:1px solid green;padding:5px; }
	#overlay1.yui-overlay .ft { border:1px solid blue;padding:5px; }

</style>
{/literal}

<div id="overlay1" style="visibility:hidden">
	<div class="bd">
		{if $editMode == 1}
		<a class="mngmntlink preview_mngmntlink" href="{link action=preview module=previewmodule}">Preview Mode</a>
		{/if}
		{if $previewMode == 1}
		<a class="mngmntlink preview_mngmntlink" href="{link action=normal module=previewmodule}">Edit Mode</a>
		{/if}
		
		<button id="demo-run" href="">collapse</button>
	
		{if $permissions.configure == 1 or $permissions.administrate == 1}
		<div class="adminmenubar">
		{assign var=i value=0}
		<div id="admincontrolpanel" class="yuiadminmenubar yuiadminmenubarnav">
		    <div class="bd">
		        <ul id="admin-top-ul" class="first-of-type">
		{foreach name=cat from=$menu key=cat item=items}

		{assign var=perm_name value=$check_permissions[$cat]}
		{if $permissions[$perm_name] == 1}

		            <li class="yuiadminmenubaritem first-of-type">
						<a class="yuiadminmenubaritemlabel" href="javascript:void(0)">{$cat}</a>
		                <div id="sub-{$i}" class="yuiadminmenu">
		                    <div class="bd">
		                        <ul class="admin-first-of-type">
								{foreach name=links from=$items item=info}
		                            <li class="yuiadminmenuitem"><a class="yuiadminmenuitemlabel" href="{link module=$info.module action=$info.action}">{$info.title}</a></li>
		                       	{/foreach}

		 						</ul>            
		                    </div>
		                </div> 

		{/if}
		{math equation="x+1" x=$i assign=i}
		{/foreach}

		            </li>
		        </ul>            
		    </div>
		</div>
		</div>{/if}
	
	
	
		
	</div>

</div>


<script>
{literal}
YAHOO.namespace("example.container");


YAHOO.example.container.overlay1 = new YAHOO.widget.Overlay("overlay1", { fixedcenter:true,
																			dragable:true,
																			visible:false,
																			width:"300px" } );
																					

YAHOO.util.Event.onContentReady("admincontrolpanel",function(){
var aMenuBar = new YAHOO.widget.MenuBar("admincontrolpanel", { visibility:true, autosubmenudisplay: true, hidedelay: 750, lazyload: true });


YAHOO.example.container.overlay1.show();

(function() {
    var attributes = {
        width: { from: 600, to: 10 }
        //height: { to: 10 }
    };
    var anim = new YAHOO.util.Anim('overlay1', attributes, 1.5, YAHOO.util.Easing. easeBothStrong);
	
    YAHOO.util.Event.on('demo-run', 'click', function() {
		//YAHOO.util.Dom.get('overlay1').innerHTML = "";
	//	YAHOO.util.Dom.get('demo-run').innerHTML = "Expand";
		
        anim.animate();
});
})();


aMenuBar.render();

});
{/literal}
</script>


