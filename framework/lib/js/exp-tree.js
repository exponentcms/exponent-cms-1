

expddtree = function(id, sGroup, config) {
	//console.debug(id.replace('ygtv',''))
	if (id) {
		//new YAHOO.util.DDTarget("addafter"+id,sGroup);
		//new YAHOO.util.DDTarget("addbefore"+id,sGroup);
		// bind this drag drop object to the
		// drag source object
		this.init("dragtable"+id, sGroup, config);
		this.initFrame();
		this.setHandleElId("draghandle"+id);
	}
};

YAHOO.extend(expddtree, YAHOO.util.DDProxy, {
	startDrag: function(x, y) {
		console.debug('yes');
		var proxy = this.getDragEl();
		var real = this.getEl();
		//var nodebeingdragged = tree.getNodeByElement(YAHOO.util.Dom.get(real.id));
		//nodebeingdragged.collapse();
		//console.debug(Dom.get(real.id.replace("section","sectionlabel")).innerHTML);
		//proxy.innerHTML = "<div class='shrinkwrap'><div id='dropindicator' class='nodrop'>&nbsp;</div><span>"+Dom.get(real.id.replace("section","sectionlabel")).innerHTML+"</span><span class='pshadow'></span></div>";
		YAHOO.util.Dom.addClass(real,"ghost");
		YAHOO.util.Dom.addClass(proxy,"ddnavproxiebeingdragged");
		//YAHOO.util.Dom.setStyle(proxy,"width",YAHOO.util.Dom.getStyle(proxy,"width")+"px");
		//console.debug(YAHOO.util.Dom.getStyle(proxy,"width"));
		YAHOO.util.Dom.setStyle(proxy,"border","1");
		DDM.refreshCache();
	},

	onDragEnter: function(e, id) {
		var srcEl = this.getEl();
		var destEl = Dom.get(id);
		var dragSecId = srcEl.getAttribute("id").replace("section","");
		var hoveredSecId = id.replace("addafter","");
		hoveredSecId = hoveredSecId.replace("addbefore",""); 

		//console.debug('hover - '+dragSecId+' over - '+hoveredSecId);

		if (YAHOO.util.Dom.hasClass(destEl,"addbefore") && dragSecId!=hoveredSecId){
			YAHOO.util.Dom.addClass(destEl,"addbefore-h");
			YAHOO.util.Dom.get("dropindicator").className ="dropattop";
		}
		if (YAHOO.util.Dom.hasClass(destEl,"addafter") && dragSecId!=hoveredSecId){
			YAHOO.util.Dom.addClass(destEl,"addafter-h");
			YAHOO.util.Dom.get("dropindicator").className ="putinbetween";
		}
		if (YAHOO.util.Dom.hasClass(destEl,"lastonthelist") && dragSecId!=hoveredSecId){
			YAHOO.util.Dom.addClass(destEl,"addafter-h");
			YAHOO.util.Dom.get("dropindicator").className ="dropatbottom";
		}
		if (YAHOO.util.Dom.hasClass(destEl,"draggables")){
			YAHOO.util.Dom.addClass(destEl,"hovered");
			YAHOO.util.Dom.get("dropindicator").className ="addtome";
		}
	},

	onDragOut: function(e, id) {
		var srcEl = this.getEl();
		var destEl = YAHOO.util.Dom.get(id);
		YAHOO.util.Dom.get("dropindicator").className ="nodrop";
		YAHOO.util.Dom.removeClass(destEl,"addbefore-h");
		YAHOO.util.Dom.removeClass(destEl,"addafter-h");
		YAHOO.util.Dom.removeClass(destEl,"hovered");
	},

	onDragDrop: function(e, id) {
		var srcEl = this.getEl();
		var destEl = YAHOO.util.Dom.get(id);

		var dragSecId = srcEl.getAttribute("id").replace("section","");
		var hoveredSecId = id.replace("addafter","");
		hoveredSecId = hoveredSecId.replace("addbefore","");


		var draggedNode = tree.getNodeByElement(YAHOO.util.Dom.get(this.id));
		var droppedOnNode = tree.getNodeByElement(YAHOO.util.Dom.get(id));

		if (YAHOO.util.Dom.hasClass(destEl,"addbefore") && dragSecId!=hoveredSecId){
			insertBeforeNode(draggedNode,droppedOnNode);
			YAHOO.util.Dom.removeClass(destEl,"addbefore-h");
		}
		if (YAHOO.util.Dom.hasClass(destEl,"addafter") && dragSecId!=hoveredSecId){
			insertAfterNode(draggedNode,droppedOnNode);
			YAHOO.util.Dom.removeClass(destEl,"addafter-h");
		}
		if (YAHOO.util.Dom.hasClass(destEl,"draggables")){
			appendToNode(draggedNode,droppedOnNode);
			YAHOO.util.Dom.removeClass(destEl,"hovered");
		}
	},

	endDrag: function(e) {
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

});




YAHOO.widget.TaskNode = function(oData, oParent, expanded, checked, draggable) {

	if (YAHOO.widget.LogWriter) {
		this.logger = new YAHOO.widget.LogWriter(this.toString());
	} else {
		this.logger = YAHOO;
	}

	if (oData) { 
		this.init(oData, oParent, expanded);
		this.setUpLabel(oData);
		this.setUpCheck(checked);
		this.draggable = draggable;
	}

};

YAHOO.extend(YAHOO.widget.TaskNode, YAHOO.widget.TextNode, {

	/**
	 * draggable is false by default,
	 * false if 0.
	 * @type boolean
	 */
	draggable: false,

	/**
	 * True if checkstate is 1 (some children checked) or 2 (all children checked),
	 * false if 0.
	 * @type boolean
	 */
	checked: false,

	/**
	 * checkState
	 * 0=unchecked, 1=some children checked, 2=all children checked
	 * @type int
	 */
	checkState: 0,

	taskNodeParentChange: function() {
		//this.updateParent();
	},


	setUpCheck: function(checked) {
		// if this node is checked by default, run the check code to update
		// the parent's display state
		if (checked && checked === true) {
			this.check();
		// otherwise the parent needs to be updated only if its checkstate 
		// needs to change from fully selected to partially selected
		} else if (this.parent && 2 == this.parent.checkState) {
			 this.updateParent();
		}

		// set up the custom event on the tree for checkClick
		/**
		 * Custom event that is fired when the check box is clicked.  The
		 * custom event is defined on the tree instance, so there is a single
		 * event that handles all nodes in the tree.  The node clicked is 
		 * provided as an argument.	 Note, your custom node implentation can
		 * implement its own node specific events this way.
		 *
		 * @event checkClick
		 * @for YAHOO.widget.TreeView
		 * @param {YAHOO.widget.Node} node the node clicked
		 */
		if (this.tree && !this.tree.hasEvent("checkClick")) {
			this.tree.createEvent("checkClick", this.tree);
		}

		this.subscribe("parentChange", this.taskNodeParentChange);

	},

	/**
	 * The id of the check element
	 * @for YAHOO.widget.TaskNode
	 * @type string
	 */
	getCheckElId: function() { 
		return "ygtvcheck" + this.index; 
	},

	/**
	 * Returns the check box element
	 * @return the check html element (img)
	 */
	getCheckEl: function() { 
		return document.getElementById(this.getCheckElId()); 
	},

	/**
	 * The style of the check element, derived from its current state
	 * @return {string} the css style for the current check state
	 */
	getCheckStyle: function() { 
		return "ygtvcheck" + this.checkState;
	},

	/**
	 * Returns the link that will invoke this node's check toggle
	 * @return {string} returns the link required to adjust the checkbox state
	 */
	getCheckLink: function() { 
		return "YAHOO.widget.TreeView.getNode(\'" + this.tree.id + "\'," + 
			this.index + ").checkClick()";
	},

	/**
	 * Invoked when the user clicks the check box
	 */
	checkClick: function() { 
		this.logger.log("previous checkstate: " + this.checkState);
		if (this.checkState === 0) {
			this.check();
		} else {
			this.uncheck();
		}

		this.onCheckClick(this);
		this.tree.fireEvent("checkClick", this);
	},

	/**
	 * Override to get the check click event
	 */
	onCheckClick: function() { 
		this.logger.log("onCheckClick: " + this);
	},

	/**
	 * Refresh the state of this node's parent, and cascade up.
	 */
	updateParent: function() { 
		var p = this.parent;

		if (!p || !p.updateParent) {
			this.logger.log("Abort udpate parent: " + this.index);
			return;
		}

		var somethingChecked = false;
		var somethingNotChecked = false;

		for (var i=0;i< p.children.length;++i) {
			if (p.children[i].checked) {
				somethingChecked = true;
				// checkState will be 1 if the child node has unchecked children
				if (p.children[i].checkState == 1) {
					somethingNotChecked = true;
				}
			} else {
				somethingNotChecked = true;
			}
		}

		if (somethingChecked) {
			p.setCheckState( (somethingNotChecked) ? 1 : 2 );
		} else {
			p.setCheckState(0);
		}

		p.updateCheckHtml();
		p.updateParent();
	},

	/**
	 * If the node has been rendered, update the html to reflect the current
	 * state of the node.
	 */
	updateCheckHtml: function() { 
		if (this.parent && this.parent.childrenRendered) {
			this.getCheckEl().className = this.getCheckStyle();
		}
	},

	/**
	 * Updates the state.  The checked property is true if the state is 1 or 2
	 * 
	 * @param the new check state
	 */
	setCheckState: function(state) { 
		this.checkState = state;
		this.checked = (state > 0);
	},

	/**
	 * Check this node
	 */
	check: function() { 
		this.logger.log("check");
		this.setCheckState(2);
		for (var i=0; i<this.children.length; ++i) {
			this.children[i].check();
		}
		this.updateCheckHtml();
		this.updateParent();
	},

	/**
	 * Uncheck this node
	 */
	uncheck: function() { 
		this.setCheckState(0);
		for (var i=0; i<this.children.length; ++i) {
			this.children[i].uncheck();
		}
		this.updateCheckHtml();
		this.updateParent();
	},

	// Overrides YAHOO.widget.TextNode
	getNodeHtml: function() { 
		this.logger.log("Generating html");
		var sb = [];

		var getNode = 'YAHOO.widget.TreeView.getNode(\'' +
						this.tree.id + '\',' + this.index + ')';


		sb[sb.length] = '<table class="dragtable" id="dragtable'+this.index+'" border="1" cellpadding="0" cellspacing="0">';
		sb[sb.length] = '<tr>';
		
		for (var i=0;i<this.depth;++i) {
			//sb[sb.length] = '<td class="' + this.getDepthStyle(i) + '"> </td>';
			sb[sb.length] = '<td class="' + this.getDepthStyle(i) + '"><div class="ygtvspacer"></div></td>';
		}

		sb[sb.length] = '<td';
		sb[sb.length] = ' id="' + this.getToggleElId() + '"';
		sb[sb.length] = ' class="' + this.getStyle() + '"';
		if (this.hasChildren(true)) {
			sb[sb.length] = ' onmouseover="this.className=';
			sb[sb.length] = 'YAHOO.widget.TreeView.getNode(\'';
			sb[sb.length] = this.tree.id + '\',' + this.index +	 ').getHoverStyle()"';
			sb[sb.length] = ' onmouseout="this.className=';
			sb[sb.length] = 'YAHOO.widget.TreeView.getNode(\'';
			sb[sb.length] = this.tree.id + '\',' + this.index +	 ').getStyle()"';
		}
		sb[sb.length] = ' onclick="javascript:' + this.getToggleLink() + '"> ';
		//sb[sb.length] = '</td>';
		sb[sb.length] = '<div class="ygtvspacer"></div></td>';

		// Dragdrop
		if(this.draggable == true){
			sb[sb.length] = '<td';
			//sb[sb.length] = '';
			sb[sb.length] = ' class="nodeDragHandle"';
			//sb[sb.length] = ' onclick="javascript:' + this.getCheckLink() + '">';
			sb[sb.length] = '">';
			sb[sb.length] = '<div id="nodeDragHandle' + this.index + '" style="border:1px solid red" class="ygtvspacer"></div></td>';
			//console.debug(this.getElId());
			//YAHOO.util.Dom.setStyle(this.getElId(), 'background', 'red');
		}
		
		// check box
		sb[sb.length] = '<td';
		sb[sb.length] = ' id="' + this.getCheckElId() + '"';
		sb[sb.length] = ' class="' + this.getCheckStyle() + '"';
		sb[sb.length] = ' onclick="javascript:' + this.getCheckLink() + '">';
		//sb[sb.length] = ' </td>';
		sb[sb.length] = '<div class="ygtvspacer"></div></td>';
		

		sb[sb.length] = '<td>';
		sb[sb.length] = '<a';
		sb[sb.length] = ' id="' + this.labelElId + '"';
		sb[sb.length] = ' class="' + this.labelStyle + '"';
		sb[sb.length] = ' href="' + this.href + '"';
		sb[sb.length] = ' target="' + this.target + '"';
		sb[sb.length] = ' onclick="return ' + getNode + '.onLabelClick(' + getNode +')"';
		if (this.hasChildren(true)) {
			sb[sb.length] = ' onmouseover="document.getElementById(\'';
			sb[sb.length] = this.getToggleElId() + '\').className=';
			sb[sb.length] = 'YAHOO.widget.TreeView.getNode(\'';
			sb[sb.length] = this.tree.id + '\',' + this.index +	 ').getHoverStyle()"';
			sb[sb.length] = ' onmouseout="document.getElementById(\'';
			sb[sb.length] = this.getToggleElId() + '\').className=';
			sb[sb.length] = 'YAHOO.widget.TreeView.getNode(\'';
			sb[sb.length] = this.tree.id + '\',' + this.index +	 ').getStyle()"';
		}
		sb[sb.length] = (this.nowrap) ? ' nowrap="nowrap" ' : '';
		sb[sb.length] = ' >';
		sb[sb.length] = this.label;
		sb[sb.length] = '</a>';
		sb[sb.length] = '</td>';
		sb[sb.length] = '</tr>';
		sb[sb.length] = '</table>';

		return sb.join("");

	},

	toString: function() {
		return "TaskNode (" + this.index + ") " + this.label;
	}
});

var treeView = YAHOO.widget.TreeView;
var expTree = function() {
	
	refreshDD = function () {
		dds = YAHOO.util.Dom.getElementsByClassName("draggables");
		for (dd in dds){
			new DDSend(dds[dd].getAttribute("id").replace("section",""));
		}
	}
	
	return {
		init:function(obj){
			tree = new treeView('nodetree');
			var root = tree.getRoot();
			var node = [];
			node[0]=root;
			
			for(o=0; o<obj.length;o++){
				parent = node[obj[o].parent_id];
				var params = {label:obj[o].name, id:obj[o].id };
				node[obj[o].id] = new YAHOO.widget.TaskNode(params, parent, false, false, obj[o].draggable);
				new expddtree(node[obj[o].id].index);
			}
			tree.draw();
			console.debug(tree);
		}
	};
}();