
/* Copyright (c) 2006 Yahoo! Inc. All rights reserved. */

/**
 * @extends YAHOO.util.DragDrop
 * @constructor
 * @param {String} handle the id of the element that will cause the resize
 * @param {String} panel id of the element to resize
 * @param {String} sGroup the group of related DragDrop items
 */
YAHOO.example.DDResize = function(panelElId, handleElId, sGroup, config) {
    if (panelElId) {
        this.init(panelElId, sGroup, config);
        this.handleElId = handleElId;
        this.setHandleElId(handleElId);
        this.logger = this.logger || YAHOO;
    }
};

// YAHOO.example.DDResize.prototype = new YAHOO.util.DragDrop();
YAHOO.extend(YAHOO.example.DDResize, YAHOO.util.DragDrop);

YAHOO.example.DDResize.prototype.onMouseDown = function(e) {
    	var panel = this.getEl();
    	this.startWidth = panel.offsetWidth;
	this.startHeight = panel.offsetHeight;

    	this.startPos = [YAHOO.util.Event.getPageX(e),
        	        YAHOO.util.Event.getPageY(e)];
	};

	YAHOO.example.DDResize.prototype.onDrag = function(e) {
    	var newPos = [YAHOO.util.Event.getPageX(e),
                     YAHOO.util.Event.getPageY(e)];

    	var offsetX = newPos[0] - this.startPos[0];
    	var offsetY = newPos[1] - this.startPos[1];

    	var newWidth = Math.max(this.startWidth + offsetX, 10);
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

	var image = YAHOO.util.Dom.get('the-image');
	image.style.width = newWidth + "px";
	image.style.height = newHeight + "px";

    	var panel = this.getEl();
    	panel.style.width = newWidth + "px";
    	panel.style.height = newHeight + "px";

	var width_input = YAHOO.util.Dom.get('img-wid');
	var height_input = YAHOO.util.Dom.get('img-hgt');
	width_input.value = newWidth;
	height_input.value = newHeight;
};

/*************************  END YAHOO FUNCTIONS   *******************************************/

function resetOrigSize() {
	var theimage = YAHOO.util.Dom.get('the-image');
	var widthinput = YAHOO.util.Dom.get('img-wid');
	var heightinput = YAHOO.util.Dom.get('img-hgt');
	var paneldiv = YAHOO.util.Dom.get('panelDiv');
	
	theimage.style.width = originalWidth;
	theimage.style.height = originalHeight;
	paneldiv.style.width = originalWidth;
	paneldiv.style.height = originalHeight;
	widthinput.value = originalWidth;
	heightinput.value = originalHeight;
}

function swapPanels(divid) {
        the_elements = YAHOO.util.Dom.getElementsByClassName('action-panel');
        for (x in the_elements) {
                if (the_elements[x].id != divid ) {
                        the_elements[x].style.display = "none";
                } else {
                        the_elements[x].style.display = "block";
			if (the_elements[x].id == 'resize-form') {
				showHandle(true);
			}
                }
        } 
}

function getActivePanel() {
	the_elements = YAHOO.util.Dom.getElementsByClassName('action-panel');
        for (x in the_elements) {
                if (the_elements[x].id != divid ) {
                        the_elements[x].style.display = "block";
                        return (the_elements[x].id);
                }
        }
}

function showHandle(show) {
	handle = YAHOO.util.Dom.get('handleDiv');
	if (show == true) {
		handle.style.display = 'block';
	} else {
		handle.style.display = 'none';
	}
}

function select(fileid) {
	all_files = YAHOO.util.Dom.getElementsByClassName('file-preview');
        for (x in all_files) {
		if (all_files[x].id != 'file-' + fileid) {
			all_files[x].class = 'file-preview';
		} else {
			console.debug("class: " + all_files[x].class);
			all_files[x].class = 'file-preview selected';
			console.debug("class: " + all_files[x].class);
			//getFileInfo(fileid);
		}
	}
}

function getFileInfo(fileid) {
	//YAHOO.util.Event.stopEvent(e); //stops normal event
        //YAHOO.util.Connect.setForm('ad_modifysrch');
        var uri = 'index.php';
	var postdata = 'module=cermi&action=get_file_info&ajax_action=1&file_id=' + fileid
        	YAHOO.util.Connect.asyncRequest('POST', uri, {
                	success : function(o) {
				eval( 'selectedFile = ' + o.responseText + ';' );
                        	//document.getElementById('file-info').innerHTML= '';
                                //document.getElementById('file-info').innerHTML= o.responseText;
                        },
                        failure : function(o) {
                        	//console.log('booo....');
                        },
                        timeout : 5000
       }, postdata);
}


