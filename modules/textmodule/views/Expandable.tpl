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
 
 <style type="text/css" media="screen">
    {* Some CSS for the initial setup *}
    {literal}
        .textmodule.expandable .bodycopy {
            overflow:hidden; 
            height: 0px;
        }
        .textmodule.expandable h2 {
			color: rgb(108, 64, 141); 
			font-family: Trebuchet MS,verdana,lucida,arial,helvetica,sans-serif; 
			font-size: 12px;
			font-weight:normal;
        }
    {/literal}
 </style>

<div class="textmodule expandable">

	{if $moduletitle != ""}
        {* here, we're setting an ID based on the id of the $textitem *}
        {* We're also giving it a classname, which is what YUI will pick up and listen for *}
        
	    <h2 id="expand{$textitem->id}" class="expandable" style="cursor:pointer"  title="See more detail">{$moduletitle} <img src="/themes/common/images/icons/down.gif" alt="See more detail"></h2>
	{/if}

	{permissions level=$smarty.const.UILEVEL_NORMAL}
	<div class="moduleactions">
		{if $permissions.edit == 1}
			{if $textitem->approved != 1}
				<img src="{$smarty.const.ICON_RELATIVE}edit.disabled.png" title="{$_TR.alt_edit_disabled}" alt="{$_TR.alt_edit_disabled}" {$smarty.const.XHTML_CLOSING}>
			{else}
				<a href="{link action=edit id=$textitem->id}" ><img src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" {$smarty.const.XHTML_CLOSING}></a>
			{/if}
		{/if}
		{if $textitem->approved != 1 && ($permissions.approve == 1 || $permissions.manage_approval == 1 || $permissions.edit == 1)}
		<a href="{link module=workflow datatype=textitem m=textmodule s=$__loc->src action=summary}">{$_TR.link_viewap}</a>
		{/if}
		{if $permissions.manage_approval == 1 && ($textitem->id != 0 && $textitem->approved != 0)}
			<a href="{link module=workflow datatype=textitem m=textmodule s=$__loc->src action=revisions_view id=$textitem->id}" title="{$_TR.link_manageap}" >
				<img src="{$smarty.const.ICON_RELATIVE}revisions.png" title="{$_TR.alt_revisions}" alt="{$_TR.alt_revisions}" {$smarty.const.XHTML_CLOSING}> 
			</a>
		{/if}
	</div>
	{/permissions}		
	
	{* Assign the expanding div an ID based again of the $textitem ID so we know what to look for *}
	
	<div id="expandcont{$textitem->id}" class="bodycopy">
		{if $textitem->approved != 0}
			{$textitem->text}
		{/if}
	</div>
</div>

{* all we need is Annimation for the yuimods *}
{script unique="expanding-content" yuimodules="animation"}
{literal}
//wait for the DOM to load
YAHOO.util.Event.onDOMReady(function(){
    // gather all elements with a class name of expandable 
    var triggers =  YAHOO.util.Dom.getElementsByClassName('expandable');
    
    // listen for any triggers to be clicked, and execute the anonymous function when they do
    YAHOO.util.Event.on(triggers, 'click', function(e){
        
        //grab the HTML element from the click event
        var target = YAHOO.util.Event.getTarget(e);
        
        // get and parse out the numeric ID from the html node
        var eid = target.id.replace("expand","");
        
        // grab the element to expand based on our new ID
        var dvToExpand = YAHOO.util.Dom.get('expandcont'+eid);
        
        //the rest is your code :)
        var to_height = (dvToExpand.offsetHeight == 0) ? dvToExpand.scrollHeight : 0;
        var from_height = (dvToExpand.offsetHeight == 0) ? 0 : dvToExpand.scrollHeight;
        var ease_type = (from_height == 0) ? YAHOO.util.Easing.easeOut : YAHOO.util.Easing.easeIn;
        var new_status = (from_height == 0) ? "Collapse" : "expand";
        var anim = new YAHOO.util.Anim(dvToExpand, { height: {to: to_height, from: from_height} }, 0.5, ease_type);
        anim.animate();
    });
});
{/literal}
{/script}
