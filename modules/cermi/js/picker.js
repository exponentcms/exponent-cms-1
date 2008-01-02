// initialize some global vars
var Dom = YAHOO.util.Dom;
var selectedFiles = new Array();

function init() {
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////   SETUP THE DRAG n DROP FOR FILES   /////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	// make the files draggable
	draggable_els = YAHOO.util.Dom.getElementsByClassName('file-preview');
	for (var i=0; i<draggable_els.length; i++) { 
		var tmpObj = new YAHOO.util.DD(draggable_els[i], 'files');
		tmpObj.onDragDrop = function(e, id) {
			var dropel = Dom.get('file-cart');
			var dragel = this.getDragEl();
			
			// check to see if the file was already dropped into the dropzone	
			var allowDrop = true;

			if (limit != 0 && selectedFiles.length >= limit) {
				allowDrop = false;
				YAHOO.container.limitdialog.show();
			}

			for(var y=0; y<selectedFiles.length; y++) {
				if(selectedFiles[y] == dragel.id) {
					allowDrop = false;
				}
			} 	

			// if the file hasn't already been selected then add it to the selectedFiles array and show it in the dropzone
			if (allowDrop == true) {	
				selectedFiles.push(dragel.id);
				dropel.innerHTML = dropel.innerHTML + '<div class="selected">' + dragel.innerHTML + '</div>';
			}
			
			Dom.setXY(dragel, this.iStartPos);
        	}
	
		tmpObj.startDrag = function(x,y) {
			this.iStartPos = Dom.getXY(this.getDragEl());
		}
		
		tmpObj.endDrag = function(x,y) {
			var dragel = this.getDragEl();
			Dom.setXY(dragel, this.iStartPos);
		}
	
		tmpObj.onDrag = function(e) {
			var dropel = Dom.get('file-cart');
			Dom.setStyle(dropel, 'background-color', '#f3c3c3');
		}
		
		tmpObj.onMouseUp = function(e) {
			var dropel = Dom.get('file-cart');
			Dom.setStyle(dropel, 'background-color', 'white');
		}
	}

	// setup up the dropzone
	var dropzone = new YAHOO.util.DDTarget("file-cart", 'files');
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////   SETUP THE SELECT BUTTON   //////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function onSelectBtnClick(p_oEvent) {
		var el = window.opener.document.getElementById(item_type);
		console.debug(el);
        	for(var i=0; i<selectedFiles.length; i++) {
                	el.innerHTML = el.innerHTML + '<input type="hidden" name=' + item_type + '[] value="' + getFileID(selectedFiles[i]) + '" />';
        	}
		window.close();
	}

	function getFileID(filename) {
        	var splitID = filename.split("-");
		console.debug(splitID);
        	return splitID[1];
	}
	var oPushButton = new YAHOO.widget.Button("selectbutton", { onclick: { fn: onSelectBtnClick } });
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////   CONFIRMATION DIALOGS   /////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	YAHOO.container.deletedialog = new YAHOO.widget.SimpleDialog("deleteconfirm", 
		 { width: "300px",
		   height: "200px",
		   fixedcenter: true,
		   visible: false,
		   draggable: true,
		   close: true,
		   text: "Are you sure you want to delete this image?",
		   icon: YAHOO.widget.SimpleDialog.ICON_HELP,
		   constraintoviewport: true,
		   buttons: [ { text:"Yes", handler:handleYes, isDefault:true },
					  { text:"No",  handler:handleNo } ]
		 } );

	YAHOO.container.deletedialog.setHeader('Are you sure?');
	YAHOO.container.deletedialog.render('scroller');

	YAHOO.container.limitdialog = new YAHOO.widget.SimpleDialog("limit",
                 { width: "350px",
                   height: "125px",
                   fixedcenter: true,
                   visible: false,
                   draggable: true,
                   close: true,
                   text: "You can only select "+limit+" file right now.",
                   icon: YAHOO.widget.SimpleDialog.ICON_WARN,
                   constraintoviewport: true,
                   buttons: [ { text:"OK", handler:handleYes, isDefault:true }],
                 } );

        YAHOO.container.limitdialog.setHeader('Limit Reached');
        YAHOO.container.limitdialog.render('scroller');

	function handleYes() {
		this.hide();
	}

	function handleNo() {
		this.hide();
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////   SETUP THE TREE VIEW   /////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	tree = new YAHOO.widget.TreeView('tree-view');
	var root = tree.getRoot();
	var tmpNode = new YAHOO.widget.TextNode('My Documents', root, false);
	var tmpNode1 = new YAHOO.widget.TextNode('My Pictures', tmpNode, false);
	var tmpNode11 = new YAHOO.widget.TextNode('PDF Files', tmpNode, false);
	var tmpNode2 = new YAHOO.widget.TextNode('Public Documents', root, false);
	tree.draw();
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////   SETUP THE CONTEXT MENU   /////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//var files = Dom.getElementsByClassName('file-preview');
	var images = Dom.get('cermi-files').getElementsByTagName('img');
	oContextMenu = new YAHOO.widget.ContextMenu("mycontextmenu", {
                                                        trigger: images,
                                                        lazyload: true, 
                                                        itemdata: [
                                                                { text: "Resize Image", onclick: { fn: resizeImage} },
                                                                { text: "Rotate Image", onclick: { fn: rotateImage } },
                                                                { text: "Crop Image", onclick: { fn: cropImage } },
                                                                { text: "Delete Image", onclick: { fn: deleteImage } },
                                                        ] });

        oContextMenu.render('cermi-files');

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////   RESIZE PANELS AND FUNCTIONS   ///////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function initializeResizePanels(imageInfo) {
		var filePath = imageInfo.directory + '/' + imageInfo.filename;
		var imgID = 'img-' + imageInfo.id
		var originalWidth = imageInfo.image_width;
		var originalHeight = imageInfo.image_height;

		var ratioDiv = YAHOO.util.Dom.get('ratio');
		ratioDiv.style.display = 'block';

		//place the panels into the scrollable region of the main panel.
		var html = '<div id="dd-panel"><img id="' + imgID + '" src="' + filePath +'" /><div id="dd-resize-handle"></div></div>';
		setScroller(html);


		//get the newly created image element

		//initialize the draggable regions
		YAHOO.example.DDResize = function(panelElId, handleElId, sGroup, config) {
	            YAHOO.example.DDResize.superclass.constructor.apply(this, arguments);
	            if (handleElId) {
	                this.setHandleElId(handleElId);
	            }
	        };

	        YAHOO.extend(YAHOO.example.DDResize, YAHOO.util.DragDrop, {

	            onMouseDown: function(e) {
	                var panel = this.getEl();

	                this.startWidth = panel.offsetWidth;
	                this.startHeight = panel.offsetHeight;

	                this.startPos = [YAHOO.util.Event.getPageX(e),
	                                 YAHOO.util.Event.getPageY(e)];
	            },

	            onDrag: function(e) {
	                var newPos = [YAHOO.util.Event.getPageX(e),
	                              YAHOO.util.Event.getPageY(e)];

	                var offsetX = newPos[0] - this.startPos[0];
	                var offsetY = newPos[1] - this.startPos[1];

	                var newWidth = Math.max(this.startWidth + offsetX, 10);
	                //var newHeight = Math.max(this.startHeight + offsetY, 10);
			
			var keep_ratio = YAHOO.util.Dom.get('keep-ratio');
			if (keep_ratio.checked == true) {
			        if (originalWidth > originalHeight) {
			                var newWidth = Math.max(this.startWidth + offsetX, 10);
			                var newHeight = Math.round(Math.max( (newWidth * this.startHeight) / this.startWidth, 10));
			        } else {
			                var newHeight = Math.max(this.startHeight + offsetY, 10);
			                var newWidth = Math.round(Math.max((newHeight * this.startWidth) / this.startHeight, 10));
			        }
			} else {
			        var newWidth = Math.max(this.startWidth + offsetX, 10);
			        var newHeight = Math.max(this.startHeight + offsetY, 10);
			}

			if (newWidth > originalWidth || newHeight > originalHeight) {
			        return true;
			}

	                var panel = this.getEl();
	                panel.style.width = newWidth + "px";
	                panel.style.height = newHeight + "px";
		
			var image = YAHOO.util.Dom.get(imgID);
			image.style.width = newWidth + "px";
			image.style.height = newHeight + "px";
	            }
	        });

	        // put the resize handle and panel drag and drop instances into different
	        // groups, because we don't want drag and drop interaction events between
	        // the two of them.
	        dd = new YAHOO.example.DDResize("dd-panel", "dd-resize-handle", "panelresize");
	        dd2 = new YAHOO.util.DD("dd-panel", "paneldrag");

	        // addInvalidHandleid will make it so a mousedown on the resize handle will 
	        // not start a drag on the panel instance.  
	        dd2.addInvalidHandleId("dd-resize-handle");
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////   MISC SUPPORTING FUNCTIONS   /////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/*function uploadFile() {
		var url='index.php';
		var frmObject = document.getElementById('uploadFrm');
		YAHOO.util.Connect.setForm(frmObject, true);
		YAHOO.util.Connect.asyncRequest('POST', url, {
        		upload : function(o){
				alert("uploaded");
			},
        		success : function(o){
				alert("success");
        		},
	                failure : function(o){
				alert("failure");
	                },
        		timeout : 5000

	        });

	}*/

	function getSelectedElementID(p_oEvent) {
		var id = this.contextEventTarget.id;
		var splitID = id.split("-");
		selectedFileID = splitID[1];
	}

	function onMenuEvent(e) {
		//oContextMenu.show();	
	}

	function resizeImage() { 
		YAHOO.util.Connect.asyncRequest('POST', 'index.php?module=cermi&action=fileinfo&ajax_action=1&id='+selectedFileID, { 
			success: function(o) {
				eval( 'selectedFile = ' + o.responseText + ';' );
				//setScroller(o.responseText);
				//setScroller(o.responseText);
				initializeResizePanels(selectedFile);
			} 
		});
	}

	function setScroller(html) {
		 var imageArea = YAHOO.util.Dom.get('scroller');
                imageArea.innerHTML = html
	}

	function rotateImage() {}
	function cropImage() {}
	function deleteImage() {
		YAHOO.container.deletedialog.show();
	}
}

(function() {
	var Dom = YAHOO.util.Dom;
	var dd, dd2, dd3, selectedFileID, mainWin, tree, oContextMenu, dropzone;
	YAHOO.namespace("container");
    	YAHOO.util.Event.onDOMReady(function() {
		init();  //run the init function
    	});
})();
//YAHOO.util.Event.addListener(window, "load", init);
