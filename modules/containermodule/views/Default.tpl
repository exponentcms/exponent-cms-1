{*
 * Copyright (c) 2004-2006 OIC Group, Inc.
 * Written and Designed by Phillip Ball (this file anyways :)
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

{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
	{if ($permissions.administrate == 1 || $permissions.edit_module == 1 || $permissions.delete_module == 1 || $permissions.add_module == 1 || $container->permissions.administrate == 1 || $container->permissions.edit_module == 1 || $container->permissions.delete_module == 1)} 
	
	{script yuimodules='container,menu' unique="tooltipAndMenu" src="`$smarty.const.URL_FULL`framework/lib/js/exp-container.js"}
	{/script}
		<div id="cont{$top->id}" class="containermodule">
	{/if}
{/permissions}



{permissions level=$smarty.const.UILEVEL_STRUCTURE}
{if $hasParent == 0 && ($permissions.edit_module == 1 || $permissions.administrate == 1)}{** top level container module **}
	<div class="container_editheader">
	
		<a id="container{$top->id}" class="modulemenutrigger" href="#" rel="{$_TR.container_module}">&nbsp;</a>
		<span class="modtype viewinfo" title="{$_TR.container_module} - {$_TR.shown_in|sprintf:$__view}">&nbsp;</span>
		
		{script unique=$top->id}
			YAHOO.expadminmenus["container{$top->id}"] = [
				{literal}{ text: "{/literal}{$_TR.menu_userperm}{literal}", classname: "userperms" , url: "{/literal}{link _common=1 action=userperms}{literal}" },
				{ text: "{/literal}{$_TR.menu_groupperm}{literal}", classname: "groupperms" , url: "{/literal}{link _common=1 action=groupperms}{literal}" }{/literal}
			];
		{/script}
		
		
	</div>
{/if}
{if $permissions.add_module == 1 && $hidebox == 0}

	<a class="addmodule" href="{link action=edit rerank=1 rank=0}"><span class="addtext">{$_TR.add_new}</span></a>

{/if}

{/permissions}

{viewfile module=$singlemodule view=$singleview var=viewfile}

{foreach key=key name=c from=$containers item=container}
	{assign var=i value=$smarty.foreach.c.iteration}
	{if $smarty.const.SELECTOR == 1}
		{include file=$viewfile}
	{else}
		<a name="mod_{$container->id}"></a> 
		{if ($permissions.administrate == 1 || $permissions.edit_module == 1 || $permissions.delete_module == 1 || $permissions.add_module == 1 || $container->permissions.administrate == 1)}
			{permissions level=$smarty.const.UILEVEL_STRUCTURE}
					
					<div id="module{$container->id}" class="container_modulewrapper">
						<div class="container_moduleheader">
							<a id="{$container->info.class}{$container->id}" class="modulemenutrigger" href="#" rel="{$container->info.module}">&nbsp;</a>
							<span class="modtype viewinfo" title="{$container->info.module}-{$_TR.shown_in|sprintf:$container->view}">&nbsp;
							{if $container->info.workflowPolicy != ""} -{$_TR.workflow|sprintf:$container->info.workflowPolicy}{/if}</span>
						</div>

						{if $i == $containers|@count}
							{assign var=last value=true}
						{else}
							{assign var=last value=false}
						{/if}
						{script unique="module`$container->id`"}
						YAHOO.expadminmenus["{$container->info.class}{$container->id}"] =  {getchromemenu module=$container rank=$i last=$last}; 
						{/script}
						
						
					
		{/permissions}
		
		{/if}
	{*<div id="{$container->info.class}-{$container->id}" class="{$container->info.class} {$container->info.class}-{$container->view|replace:' ':'-'|lower}">*}
	{$container->output}
	{*</div>*}

	{permissions level=$smarty.const.UILEVEL_STRUCTURE}
	{if ($permissions.administrate == 1 || $permissions.edit_module == 1 || $permissions.delete_module == 1 || $permissions.add_module == 1 || $container->permissions.administrate == 1 || $container->permissions.edit_module == 1 || $container->permissions.delete_module == 1)} 
	</div>
	{/if}
	{/permissions}


	{permissions level=$smarty.const.UILEVEL_STRUCTURE}
	{if $permissions.add_module == 1 && $hidebox == 0}
	<a class="addmodule" href="{link action=edit rerank=1 rank=$smarty.foreach.c.iteration}"><span class="addtext">{$_TR.add_new}</span></a>
	{/if}
	{/permissions}
	{/if}
{/foreach}


{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if ($permissions.administrate == 1 || $permissions.edit_module == 1 || $permissions.delete_module == 1 || $permissions.add_module == 1 || $container->permissions.administrate == 1 || $container->permissions.edit_module == 1 || $container->permissions.delete_module == 1)} 
<div style="clear:both"></div>
</div>
{/if}
{/permissions}


{if $permissions.administrate == 1}

{*script unique="dragmods" yuimodules='"dragdrop","json","animation","connection"'}
{literal}

(function() {

var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var DDM = YAHOO.util.DragDropMgr;
var JSON = YAHOO.lang.JSON;


eXp.DDApp = {
	init: function() {

		var ddcontainer = YAHOO.util.Dom.getElementsByClassName("containermodule","div");
		var ddmodule = YAHOO.util.Dom.getElementsByClassName("container_modulewrapper","div");
		

		for (i=0;i<ddcontainer.length;i++) {
			new YAHOO.util.DDTarget(ddcontainer[i]);
		}
		
		for (j=0;j<ddmodule.length;j++) {
			new eXp.DDList(ddmodule[j]);
		}
		
		this.initialOrder = JSON.stringify(this.showOrder());

	},

	showOrder: function() {
		var ddcontainer = YAHOO.util.Dom.getElementsByClassName("containermodule","div");
		//var ddmodule = YAHOO.util.Dom.getElementsByClassName("container_modulewrapper","div");
		
		for (i=0;i<ddcontainer.length;i++) {
			ddcontainer[i].ddmodule = YAHOO.util.Dom.getElementsByClassName("container_modulewrapper","div",ddcontainer[i]);
		}
		
		var container = {};
		
		for (i=0;i<ddcontainer.length;i++) {
			
			container[i] = {container:ddcontainer[i].getAttribute("id").replace('container',''),module:{}};
			
			for (j=0;j<ddcontainer[i].ddmodule.length;j++) {
				
				container[i].module[j] = ddcontainer[i].ddmodule[j].getAttribute("id").replace('module','');
			}
		}
		
		return container;
		
	},
	saveNewOrder: function() {
		var newOrder = JSON.stringify(this.showOrder());
		if (newOrder != this.initialOrder) {
			//console.debug(newOrder);
			
			var iUrl = eXp.URL_FULL+"index.php?ajax_action=1&module=containermodule&action=ddReorder";
			YAHOO.util.Connect.asyncRequest('POST', iUrl, 
			{
				success : function (o){
					this.initialOrder = newOrder;
				},
				failure : function(o){

				},
				timeout : 50000
			},"neworder="+newOrder);
			
		}
	}
	
	
	
};

//////////////////////////////////////////////////////////////////////////////
// custom drag and drop implementation
//////////////////////////////////////////////////////////////////////////////

eXp.DDList = function(id, sGroup, config) {

	eXp.DDList.superclass.constructor.call(this, id, sGroup, config);

	this.logger = this.logger || YAHOO;
	var el = this.getDragEl();
	//console.debug(YAHOO.util.Dom.getElementsByClassName("container_moduleheader","div",this.getEl()));
	this.setHandleElId(YAHOO.util.Dom.getElementsByClassName("container_moduleheader","div",this.getEl()));
	
	//Dom.setStyle(el, "opacity", 0.67); // The proxy is slightly transparent

	this.goingUp = false;
	this.lastY = 0;
};

YAHOO.extend(eXp.DDList, YAHOO.util.DDProxy, {

	startDrag: function(x, y) {
		this.logger.log(this.id + " startDrag");

		// make the proxy look like the source element
		var dragEl = this.getDragEl();
		var clickEl = this.getEl();
		Dom.setStyle(clickEl, "visibility", "hidden");

		dragEl.innerHTML = '<div style="height:15px; background:#777; "></div>';
		
		Dom.setStyle(dragEl, "background","blue");
		Dom.setStyle(dragEl, "opacity",".6");
		
		Dom.setStyle(YAHOO.util.Dom.getElementsByClassName("containermenu","div"), "display","none");
		
		Dom.setStyle(YAHOO.util.Dom.getElementsByClassName("containermenu","div",dragEl), "display","none");

	},

	endDrag: function(e) {

		var srcEl = this.getEl();
		var proxy = this.getDragEl();

		// Show the proxy element and animate it to the src element's location
		Dom.setStyle(proxy, "visibility", "");
		var a = new YAHOO.util.Motion( 
			proxy, { 
				points: { 
					to: Dom.getXY(srcEl)
				}
			}, 
			0.2, 
			YAHOO.util.Easing.easeOut 
		)
		var b = new YAHOO.util.Motion( 
			srcEl, { 
				points: { 
					to: Dom.getXY(srcEl)
				}
			}, 
			0.2, 
			YAHOO.util.Easing.easeOut 
		)
		var proxyid = proxy.id;
		var thisid = this.id;

		// Hide the proxy and show the source element when finished with the animation
		a.onComplete.subscribe(function() {
				Dom.setStyle(YAHOO.util.Dom.getElementsByClassName("containermenu","div"), "display","block");
				Dom.setStyle(YAHOO.util.Dom.getElementsByClassName("containermenu","div",proxy), "display","none");
				Dom.setStyle(proxyid, "visibility", "hidden");
				Dom.setStyle(thisid, "visibility", "");
			});
		a.animate();
		eXp.DDApp.saveNewOrder();
	},

	onDragDrop: function(e, id) {

		// If there is one drop interaction, the li was dropped either on the list,
		// or it was dropped on the current location of the source element.
		if (DDM.interactionInfo.drop.length === 1) {

			//console.debug(dragEl);

			// The position of the cursor at the time of the drop (YAHOO.util.Point)
			var pt = DDM.interactionInfo.point; 

			// The region occupied by the source element at the time of the drop
			var region = DDM.interactionInfo.sourceRegion; 

			// Check to see if we are over the source element's location.  We will
			// append to the bottom of the list once we are sure it was a drop in
			// the negative space (the area of the list without any list items)
			if (!region.intersect(pt)) {
				var destEl = Dom.get(id);
				var destDD = DDM.getDDById(id);
				destEl.appendChild(this.getEl());
				destDD.isEmpty = false;
				DDM.refreshCache();
			}

		}
	},

	onDrag: function(e) {

		// Keep track of the direction of the drag for use during onDragOver
		var y = Event.getPageY(e);

		if (y < this.lastY) {
			this.goingUp = true;
		} else if (y > this.lastY) {
			this.goingUp = false;
		}

		this.lastY = y;
	},

	onDragOver: function(e, id) {
	
		var srcEl = this.getEl();
		var destEl = Dom.get(id);
	//	console.debug(id);
		
		// We are only concerned with list items, we ignore the dragover
		// notifications for the list.
		if (destEl.getAttribute("class") == "container_modulewrapper") {
			var orig_p = srcEl.parentNode;
			var p = destEl.parentNode;

			if (this.goingUp) {
				p.insertBefore(srcEl, destEl); // insert above
			} else {
				p.insertBefore(srcEl, destEl.nextSibling); // insert below
			}

			DDM.refreshCache();
		}
	}
});

Event.onDOMReady(eXp.DDApp.init, eXp.DDApp, true);

})();
{/literal}
{/script*}

{/if}
