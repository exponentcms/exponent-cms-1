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
<link rel="stylesheet" type="text/css" href="{$smarty.const.URL_FULL}external/yui/build/menu/assets/skins/sam/menu.css">
<link rel="stylesheet" type="text/css" href="{$smarty.const.URL_FULL}external/yui/build/treeview/assets/skins/sam/treeview.css">
{literal}

<style type="text/css" media="screen">
	table {
		display:table;
		width:100%;
	}
	th strong {
		color: #fff;
	}
	tr {
		height: none;
		background: none;	
	}
	td {
		padding:0 0 0 0;
		border: none; 
		height:none
	}
	.yui-skin-sam .bd {
		background:white;
		border:1px solid #444;
	}
	.not-hovered {
		background:#fff;
	}
	.hovered {
		background:#dedede;
		color:white;
		font-weight:bold;
		border:1px dotted #777;
	}
	.draggables {
		width:100%;
		padding:0;
		color:#010101;
	}
	.draggables:hover {
		background:#dedede;
		cursor:context-menu;
	}
	.ghost {
		filter: alpha(opacity=30); -moz-opacity:.3;
	}
	.draghandle {
		margin: 0 10px;
		cursor:move;
	}
	.addafter {
		width:100%;
		font-size:100%;
		height:3px;
		margin-top:1px;
	}
	.addafter-h {
		background:#000;
	}
	.beingdragged {
		background:none;
		width:none;
		text-align:left;
		border:none;
	}
	
	
	
	
	
</style>

{/literal}


<div class="yui-skin-sam navigationmodule manager-hierarchy">
		<div class="form_header">
				<h1>{$_TR.form_title}</h1>
				<p>{$_TR.form_header}</p>
		</div>
		<a class="newpage" href="{link action=add_section parent=0}">{$_TR.new_top_level}</a>
		

		<div id="navtree"><img src="{$smarty.const.ICON_RELATIVE}ajax-loader.gif">  Loading Navigation</div>


</div>

{script yuimodules="'treeview','animation','menu','dragdrop','json','container','connection'"}
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

		proxy.innerHTML = real.innerHTML;
		YAHOO.util.Dom.addClass(real,"ghost");
		YAHOO.util.Dom.addClass(proxy,"beingdragged");
		proxy.style.border="none";

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
		 ////console.debug(droppedOnNode); 
		 ////console.debug(destEl); 
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
				YAHOO.util.Dom.removeClass(proxy,"beingdragged");
			});
		a.animate();

	}
	
	refreshDD = function () {
		dds = YAHOO.util.Dom.getElementsByClassName("draggables");
		////console.debug(dds);
		for each (dd in dds){
			//console.debug(dd.getAttribute("id"));
			new DDSend(dd.getAttribute("id").replace("section",""));
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
		tree.popNode(moveMe);
		moveMe.insertAfter(moveMeAfter);
		saveToDB(moveMe.data.id,moveMeAfter.data.id,"after");
		tree.getRoot().refresh();
	}
	
	function appendToNode(moveMe,moveMeUnder) {
		
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
		window.location="{/literal}{$smarty.const.URL_FULL}{literal}index.php?module=navigationmodule&_common=1&action=userperms&id="+currentMenuNode.data.id;
	}

	function editGroupPerms (){
		window.location="{/literal}{$smarty.const.URL_FULL}{literal}index.php?module=navigationmodule&_common=1&action=groupperms&id="+currentMenuNode.data.id;
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
		
		//We'll load node data based on what we get back when we
		//use Connection Manager topass the text label of the 
		//expanding node to the Yahoo!
		//Search "related suggestions" API.	 Here, we're at the 
		//first part of the request -- we'll make the request to the
		//server.  In our success handler, we'll build our new children
		//and then return fnLoadComplete back to the tree.
		
		//Get the node's label and urlencode it; this is the word/s
		//on which we'll search for related words:
		
		//onsole.debug(node.data);
		
		var nodeid = encodeURI(node.data.id);
		
		//prepare URL for XHR request:
		var sUrl = "{/literal}{$smarty.const.URL_FULL}{literal}index.php?ajax_action=1&module=navigationmodule&action=returnChildrenAsJSON&id=" + nodeid;
		
		//prepare our callback object
		var callback = {
		
			//if our XHR call is successful, we want to make use
			//of the returned data and create child nodes.
			success: function(oResponse) {
				//YAHOO.log("XHR transaction was successful.", "info", "example");
				//YAHOO.log(oResponse.responseText);
				var oResults = YAHOO.lang.JSON.parse(oResponse.responseText);
				////console.debug(oResults);
				if((oResults) && (oResults.length)) {
					//Result is an array if more than one result, string otherwise
					if(YAHOO.lang.isArray(oResults)) {
						for (var i=0, j=oResults.length; i<j; i++) {
							var tempNode = new YAHOO.widget.HTMLNode({id:oResults[i].id,name:oResults[i].name,html:buildHTML(oResults[i])}, node, false, true)
						}
					} else {
						//there is only one result; comes as string:
						var tempNode = new YAHOO.widget.HTMLNode({id:oResults.id,name:oResults.name,html:buildHTML(oResults)}, node, false, true)
					}
				}
				
				//When we're done creating child nodes, we execute the node's
				//loadComplete callback method which comes in via the argument
				//in the response object (we could also access it at node.loadComplete,
				//if necessary):
				
				refreshDD();
				
				oResponse.argument.fnLoadComplete();
			},
			
			//if our XHR call is not successful, we want to
			//fire the TreeView callback and let the Tree
			//proceed with its business.
			failure: function(oResponse) {
				YAHOO.log("Failed to process XHR transaction.", "info", "example");
				oResponse.argument.fnLoadComplete();
			},
			
			//our handlers for the XHR response will need the same
			//argument information we got to loadNodeData, so
			//we'll pass those along:
			argument: {
				"node": node,
				"fnLoadComplete": fnLoadComplete
			},
			
			//timeout -- if more than 7 seconds go by, we'll abort
			//the transaction and assume there are no children:
			timeout: 7000
		};
		
		//With our callback object ready, it's now time to 
		//make our XHR call using Connection Manager's
		//asyncRequest method:
		YAHOO.util.Connect.asyncRequest('GET', sUrl, callback);
	}

	function buildTree() {
	   //create a new tree:
	   tree = new YAHOO.widget.TreeView("navtree");
	   
	   //turn dynamic loading on for entire tree:
	   tree.setDynamicLoad(loadNodeData, currentIconMode);
	   
	   //get root node for tree:
	   var root = tree.getRoot();
	   
	   //add child nodes for tree; our top level nodes are
	   //all the states in India:
	   var nav = {/literal}{getnav type="siblings" of=$sections[0]->id json=true assign="jsonstring"}{$jsonstring}{literal};
	   //var nav = [{name:"My Exponent Website",id:0}];
	   
	   for (var i=0, j=nav.length; i<j; i++) {
			var tempNode = new YAHOO.widget.HTMLNode({id:nav[i].id,name:nav[i].name,html:buildHTML(nav[i])}, root, false, true);
		}
		
		tree.createEvent("nodemoved");
		tree.subscribe("expandComplete",refreshDD);
		tree.subscribe("collapseComplete",refreshDD);
		tree.subscribe("nodemoved",refreshDD);
	   
	   //render tree with these toplevel nodes; all descendants of these nodes
	   //will be generated as needed by the dynamic loader.
	   	tree.draw();
		refreshDD();
	}
	
	function buildHTML(section) {

		var drag = (section.id!=0)?'<img class="draghandle" id="draghandle'+section.id+'" src="{/literal}{$smarty.const.URL_FULL}{literal}themes/common/images/icons/drag.gif">':'';
		
		var html = '<div class="draggables" id="section'+section.id+'" href="'+section.link+'">'+drag+section.name+'</div><div class="addafter" id="addafter'+section.id+'"></div>';

		return html;
	}

	
	// context menu
	
	var currentMenuNode = null;
	
	function onTriggerContextMenu(p_oEvent) {
		
		var theID = this.contextEventTarget;
		if(YAHOO.util.Dom.hasClass(theID,"draggables")){
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

			buildTree();
		}
	}
	
	
} ();

//once the DOM has loaded, we can go ahead and set up our tree:
YAHOO.util.Event.onDOMReady(eXp.ddNavTree.init, eXp.ddNavTree,true)



{/literal}
{/script}




