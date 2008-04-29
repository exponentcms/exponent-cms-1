{*
 * Copyright (c) 2004-2008 OIC Group, Inc.
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

<style type="text/css" media="screen">
	
</style>

{/literal}
<link rel="stylesheet" type="text/css" href="{$smarty.const.URL_FULL}external/yui/build/treeview/assets/skins/sam/treeview.css">
<link rel="stylesheet" type="text/css" href="{$smarty.const.URL_FULL}external/yui/build/menu/assets/skins/sam/menu.css">


<div class="navigationmodule manager-hierarchy">

		<div class="form_header">
				<h1>{$_TR.form_title}</h1>
				<p>{$_TR.form_header}</p>
		</div>
		<a class="newpage" href="{link action=add_section parent=0}">{$_TR.new_top_level}</a>
		<div id="navtree"><img src="{$smarty.const.ICON_RELATIVE}ajax-loader.gif">	<strong>Loading Navigation</strong></div>
</div>

{script yuimodules="'treeview','menu','animation','dragdrop','json','container','connection'" unique="DDTreeNav" yuideptype="js"}
{literal} 

eXp.ddNavTree = function() {
//////////////////////////////////////////////////////////////////////////////
// dragdrop
//////////////////////////////////////////////////////////////////////////////
	
	var Dom = YAHOO.util.Dom;
	var Event = YAHOO.util.Event;
	var DDM = YAHOO.util.DragDropMgr;

	DDSend = function(id, sGroup, config) {
		////console.debug(id)
		if (id) {
			new YAHOO.util.DDTarget("addafter"+id,sGroup);
			// bind this drag drop object to the
			// drag source object
			this.init("section"+id, sGroup, config);
			this.initFrame();
			this.setHandleElId("draghandle"+id);
		}
	};

	DDSend.prototype = new YAHOO.util.DDProxy();

	DDSend.prototype.startDrag = function(x, y) {
		var proxy = this.getDragEl();
		var real = this.getEl();
		//console.debug(Dom.get(real.id.replace("section","sectionlabel")).innerHTML);
		proxy.innerHTML = "<div id='dropindicator' class='nodrop'>&nbsp;</div><span>"+Dom.get(real.id.replace("section","sectionlabel")).innerHTML+"</span>";
		YAHOO.util.Dom.addClass(real,"ghost");
		YAHOO.util.Dom.addClass(proxy,"ddnavproxiebeingdragged");
		//YAHOO.util.Dom.setStyle(proxy,"width",YAHOO.util.Dom.getStyle(proxy,"width")+"px");
		//console.debug(YAHOO.util.Dom.getStyle(proxy,"width"));
		proxy.style.border="3px solid";
		var nodebeingdragged = tree.getNodeByElement(YAHOO.util.Dom.get(real.id));
		nodebeingdragged.hideChildren();

	};

	DDSend.prototype.onDragEnter = function(e, id) {
		var srcEl = this.getEl();
		var destEl = Dom.get(id);
		var dragSecId = srcEl.getAttribute("id").replace("section","");
		var hoveredSecId = id.replace("addafter","");
		
		//console.debug('hover - '+dragSecId+' over - '+hoveredSecId);
		
		if (YAHOO.util.Dom.hasClass(destEl,"addafter") && dragSecId!=hoveredSecId){
			YAHOO.util.Dom.addClass(destEl,"addafter-h");
		}
		if (YAHOO.util.Dom.hasClass(destEl,"draggables")){
			YAHOO.util.Dom.addClass(destEl,"hovered");
		}
	};

	DDSend.prototype.onDragOut = function(e, id) {
		var srcEl = this.getEl();
		var destEl = Dom.get(id);
		if (YAHOO.util.Dom.hasClass(destEl,"addafter")){
			YAHOO.util.Dom.removeClass(destEl,"addafter-h");
		}
		if (YAHOO.util.Dom.hasClass(destEl,"draggables")){
			YAHOO.util.Dom.removeClass(destEl,"hovered");
		}
	}

	DDSend.prototype.onDragDrop = function(e, id) {
		var srcEl = this.getEl();
		var destEl = Dom.get(id);
		var dragSecId = srcEl.getAttribute("id").replace("section","");
		var hoveredSecId = id.replace("addafter","");
		
		var draggedNode = tree.getNodeByElement(YAHOO.util.Dom.get(this.id));
		var droppedOnNode = tree.getNodeByElement(YAHOO.util.Dom.get(id));

		if (YAHOO.util.Dom.hasClass(destEl,"addafter") && dragSecId!=hoveredSecId){
			insertAfterNode(draggedNode,droppedOnNode);
			YAHOO.util.Dom.removeClass(destEl,"addafter-h");
		}
		if (YAHOO.util.Dom.hasClass(destEl,"draggables")){
			appendToNode(draggedNode,droppedOnNode);
			YAHOO.util.Dom.removeClass(destEl,"hovered");
		}
	}

	DDSend.prototype.endDrag = function(e) {
		var proxy = this.getDragEl();
		var real = this.getEl();

		Dom.setStyle(proxy, "visibility", "");
		var a = new YAHOO.util.Motion( 
			proxy, { 
				points: { 
					to: Dom.getXY(real)
				}
			}, 
			0.2, 
			YAHOO.util.Easing.easeOut 
		)
		
		var proxyid = proxy.id;
		var thisid = this.id;

		// Hide the proxy and show the source element when finished with the animation
		a.onComplete.subscribe(function() {
				Dom.setStyle(proxyid, "visibility", "hidden");
				Dom.setStyle(thisid, "visibility", "");
				YAHOO.util.Dom.removeClass(real,"ghost");
				YAHOO.util.Dom.removeClass(proxy,"ddnavproxiebeingdragged");
			});
		a.animate();

	}
	
	refreshDD = function () {
		dds = YAHOO.util.Dom.getElementsByClassName("draggables");
		////console.debug(dds);
		for (dd in dds){
			//console.debug(dd.getAttribute("id"));
			new DDSend(dds[dd].getAttribute("id").replace("section",""));
		}
	}
	
//////////////////////////////////////////////////////////////////////////////
// tree
//////////////////////////////////////////////////////////////////////////////
	var tree, currentIconMode;
	
	ddarray = new Array;

	function changeIconMode() {
		var newVal = parseInt(this.value);
		if (newVal != currentIconMode) {
			currentIconMode = newVal;
		}
		buildTree();
	}

	function insertAfterNode(moveMe,moveMeAfter) {
		if(moveMe.data.id!=moveMeAfter.data.id){
			tree.popNode(moveMe);
			moveMe.insertAfter(moveMeAfter);
			saveToDB(moveMe.data.id,moveMeAfter.data.id,"after");
			tree.getRoot().refresh();
		}
	}
	
	function appendToNode(moveMe,moveMeUnder) {
		if(moveMe.data.id!=moveMeUnder.data.id){
			saveToDB(moveMe.data.id,moveMeUnder.data.id,"append");
			tree.popNode(moveMe);
			//moveMeUnder.expand();
			//tree.subscribe("expand",moveMeUnder.expand);
			if (moveMeUnder.expanded==true){			
				if(moveMeUnder.children[0]){
					moveMe.insertBefore(moveMeUnder.children[0]);
				} else {
					moveMe.appendTo(moveMeUnder);
				}
			} else {
			//	tree.getRoot().refresh();
			}
			tree.getRoot().refresh();
		}
		
	}
	
	function addSubNode (){
		window.location="{/literal}{$smarty.const.URL_FULL}{literal}index.php?module=navigationmodule&action=add_section&parent="+currentMenuNode.data.id;
	}
	
	function editNode (){
		window.location="{/literal}{$smarty.const.URL_FULL}{literal}index.php?module=navigationmodule&action=edit_contentpage&id="+currentMenuNode.data.id;
	}
	
	function deleteNode (){
		var handleYes = function() {
			this.hide();
			window.location="{/literal}{$smarty.const.URL_FULL}{literal}index.php?module=navigationmodule&action=remove&id="+currentMenuNode.data.id;
		};
		var handleNo = function() {
			this.hide();
		};

		var message = "Deleting a page moves it to the Standalone Page Manager, removing it from the Site Hierarchy. If there are any sub-pages to this section, those will also be moved";

		YAHOO.namespace("example.container");

		// Instantiate the Dialog
		YAHOO.example.container.simpledialog1 = new YAHOO.widget.SimpleDialog("simpledialog1",
										{ width: "400px",
											fixedcenter: true,
											visible: false,
											modal: true,
											draggable: false,
											close: true,
											text: message,
											icon: YAHOO.widget.SimpleDialog.ICON_HELP,
											constraintoviewport: true,
											buttons: [ { text:"Move to Standalone", handler:handleYes, isDefault:true },
												{ text:"Cancel",  handler:handleNo } ]
										} );
		YAHOO.example.container.simpledialog1.setHeader("Remove \""+currentMenuNode.data.name+"\" from hierarchy");
		
		// Render the Dialog
		YAHOO.example.container.simpledialog1.render(document.body);
		YAHOO.example.container.simpledialog1.show();

	}

	function editUserPerms (){
		window.location="{/literal}{$smarty.const.URL_FULL}{literal}index.php?module=navigationmodule&_common=1&action=userperms&int="+currentMenuNode.data.id;
	}

	function editGroupPerms (){
		window.location="{/literal}{$smarty.const.URL_FULL}{literal}index.php?module=navigationmodule&_common=1&action=groupperms&int="+currentMenuNode.data.id;
	}
	
	
	function saveToDB(move,target,type) {
		var iUrl = eXp.URL_FULL+"index.php?ajax_action=1&module=navigationmodule&action=DragnDropReRank";
		YAHOO.util.Connect.asyncRequest('POST', iUrl, 
		{
			success : function (o){
				refreshDD();
			},
			failure : function(o){
				
			},
			timeout : 50000
		},"move="+move+"&target="+target+"&type="+type);


	}

	function loadNodeData(node, fnLoadComplete)	 {
		var nodeid = encodeURI(node.data.id);
		var sUrl = eXp.URL_FULL+"index.php?ajax_action=1&module=navigationmodule&action=returnChildrenAsJSON&id=" + nodeid;
		var callback = {
			success: function(oResponse) {
				var oResults = YAHOO.lang.JSON.parse(oResponse.responseText);
				if((oResults.data) && (oResults.data.length)) {
					//Result is an array if more than one result, string otherwise
					if(YAHOO.lang.isArray(oResults.data)) {
						for (var i=0, j=oResults.data.length; i<j; i++) {
							var tempNode = new YAHOO.widget.HTMLNode({id:oResults.data[i].id,name:oResults.data[i].name,html:buildHTML(oResults.data[i])}, node, false, true)
						}
					} else {
						//there is only one result; comes as string:
						var tempNode = new YAHOO.widget.HTMLNode({id:oResults.data.id,name:oResults.data.name,html:buildHTML(oResults.data)}, node, false, true)
					}
				}
				//refresh DragDrop Cache
				refreshDD();
				oResponse.argument.fnLoadComplete();
			},
			failure: function(oResponse) {
				YAHOO.log("Failed to process XHR transaction.", "info", "example");
				oResponse.argument.fnLoadComplete();
			},
			argument: {
				"node": node,
				"fnLoadComplete": fnLoadComplete
			},timeout: 7000
		};
		YAHOO.util.Connect.asyncRequest('GET', sUrl, callback);
	}

	function buildTree(topnav) {
		//create a new tree:
		tree = new YAHOO.widget.TreeView("navtree");

		//turn dynamic loading on for entire tree:
		tree.setDynamicLoad(loadNodeData, currentIconMode);

		//get root node for tree:
		var root = tree.getRoot();

		for (var i=0, j=topnav.length; i<j; i++) {
			var tempNode = new YAHOO.widget.HTMLNode({id:topnav[i].id,name:topnav[i].name,html:buildHTML(topnav[i])}, root, false, true);
		}

		tree.createEvent("nodemoved");
		tree.subscribe("expandComplete",refreshDD);
		tree.subscribe("collapseComplete",refreshDD);
		tree.subscribe("nodemoved",refreshDD);
	   
		tree.draw();
		refreshDD();
		YAHOO.util.Event.on("expall","click",tree.expandAll,tree,true);
		YAHOO.util.Event.on("colall","click",tree.collapseAll,tree,true);
		
	}
	
	function buildHTML(section) {
		var draggable = (section.manage!=false)? 'draggables' : 'nondraggables' ;
		var dragafters = (section.manage!=false)? 'addafter' : 'cannotaddafter' ;
		var menu = '';
	//	var menu = (section.manage!=false)?'<a class="sectionmenu" href="#">&nbsp;</a>':'';
		var drag = (section.manage!=false)?'<div class="draghandle" id="draghandle'+section.id+'">&nbsp;</div>':'';
		var html = '<div class="'+draggable+'" id="section'+section.id+'" href="'+section.link+'">'+menu+drag+'<span class="sectionlabel" id="sectionlabel'+section.id+'">'+section.name+'</span></div><div class="'+dragafters+'" id="addafter'+section.id+'"></div>';
		return html;
	}
	
	function initTree (){
		var sUrl = eXp.URL_FULL+"index.php?ajax_action=1&module=navigationmodule&action=returnChildrenAsJSON&id="+0;
		var callback = {
			success: function(oResponse) {
				var oResults = YAHOO.lang.JSON.parse(oResponse.responseText);
				buildTree(oResults.data);
			},
			failure: function(oResponse) {
				YAHOO.log("Failed to process XHR transaction.", "info", "example");
			},
			timeout: 7000,
			scope: callback
		};
		YAHOO.util.Connect.asyncRequest('GET', sUrl, callback);
	}

	// context menu 
	var currentMenuNode = null;
	
	function onTriggerContextMenu(p_oEvent) {
		var theID = this.contextEventTarget;
		if(YAHOO.util.Dom.hasClass(theID,"sectionlabel")){
			currentMenuNode = tree.getNodeByElement(theID);
			oContextMenu.setItemGroupTitle(currentMenuNode.data.name,0);
		} else {
			this.cancel();
		}
	}
	
	var navoptions = [
			{ classname:"addsubpage", text: "Add A Subpage", onclick: { fn: addSubNode } },
			{ classname:"editpage", text: "Edit This Page", onclick: { fn: editNode } },
			{ classname:"deletepage", text: "Delete This Page", onclick: { fn: deleteNode } },
			{ classname:"userperms", text: "Manage User Permissions", onclick: { fn: editUserPerms } },
			{ classname:"groupperms", text: "Manage Group Permissions", onclick: { fn: editGroupPerms } }
		];																	
	
	
	var oContextMenu = new YAHOO.widget.ContextMenu("navTreeContext", {
																	trigger: "navtree",
																	hidedelay:1000,
																	classname: "yui-skin-sam",
																	itemdata:navoptions,
																	lazyload: true
																	 });
	oContextMenu.subscribe("triggerContextMenu", onTriggerContextMenu); 

	

	return {
		init: function() {
			YAHOO.util.Event.on(["mode0", "mode1"], "click", changeIconMode);
			var el = document.getElementById("mode1");
			if (el && el.checked) {
				currentIconMode = parseInt(el.value);
			} else {
				currentIconMode = 0;
			}

			initTree();
		}
	}
	
	
} ();

//once the DOM has loaded, we can go ahead and set up our tree:
YAHOO.util.Event.onDOMReady(eXp.ddNavTree.init, eXp.ddNavTree,true)



{/literal}
{/script}




