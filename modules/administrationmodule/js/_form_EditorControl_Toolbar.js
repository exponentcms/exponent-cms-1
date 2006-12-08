//imported htmlarea control, because file is lost during reorg
//##################################################
//#
//# Copyright (c) 2004-2006 OIC Group, Inc.
//# Copyright (c) 2006 Maxim Mueller
//# Written and Designed by James Hunt
//#
//# This file is part of Exponent
//#
//# Exponent is free software; you can redistribute
//# it and/or modify it under the terms of the GNU
//# General Public License as published by the Free
//# Software Foundation; either version 2 of the
//# License, or (at your option) any later version.
//#
//# GPL: http://www.gnu.org/licenses/gpl.txt
//#
//##################################################

//initialize the namespace object
if(! eXp.WYSIWYG) {
	eXp.WYSIWYG = new Object();
}

//TODO: move to separate file
eXp._TR = {
};



var used = new Array();
var rows = new Array();

var g_row = 0;
var g_pos = 0;

var lastCursor = null;


eXp.WYSIWYG.recurseClear = function(elem) {
	while (elem.childNodes.length) {
		elem.removeChild(elem.firstChild);
	}
}

eXp.WYSIWYG.createRow = function() {
	rows.push(new Array());
	eXp.WYSIWYG.buildToolbar();
}

eXp.WYSIWYG.deleteRow = function(rownum) {
	for (key in rows[rownum]) {
		for (key2 in used) {
			if (used[key2] == rows[rownum][key]) {
				var myButton = document.getElementById("toolboxButton_" + used[key2]);
				
				//ie6 hack
				if (document.all) {
					myButton.className = "editorcontrol_toolboxbutton";
					myButton.attachEvent('onclick', function() { 
						eXp.WYSIWYG.register(event.srcElement.holding);
					});
					myButton.holding = used[key2];
				} else {
					myButton.setAttribute("class", 'editorcontrol_toolboxbutton');
					myButton.setAttribute("onclick", "eXp.WYSIWYG.register('" + used[key2] + "')");
				}
				used.splice(key2,1);
			}
		}
	}
	rows.splice(rownum,1);
	eXp.WYSIWYG.buildToolbar();
}

eXp.WYSIWYG.createCursor = function(rownum, pos) {
	
	myCursor = document.createElement("div");
	
	//ie6 hack
	if (document.all) {
		myCursor.attachEvent('onclick',function() {
			eXp.WYSIWYG.selectCursor(event.srcElement, rownum, pos);
		});
		myCursor.className = "editorcontrol_cursor";
	} else {
		myCursor.setAttribute("onclick","eXp.WYSIWYG.selectCursor(this, " + rownum + ", " + pos + ");");
		myCursor.setAttribute("class", "editorcontrol_cursor");
	}
	
	eXp.WYSIWYG.selectCursor(myCursor, rownum, pos);
	return myCursor;
}


eXp.WYSIWYG.selectCursor = function(myCursor, new_row, new_pos) {
	g_pos = new_pos;
	g_row = new_row;

	//ie6 hack
	if (document.all) {
		//in case this is our first cursor, there is no lastCursor
		if(lastCursor) {
			lastCursor.className = "editorcontrol_cursor";
		}
		myCursor.className = "editorcontrol_cursor_selected";
	} else {
		//in case this is our first cursor, there is no lastCursor
		if(lastCursor) {
			lastCursor.setAttribute("class", "editorcontrol_cursor");
		}
		myCursor.setAttribute("class", "editorcontrol_cursor_selected");
	}
	lastCursor = myCursor;
}


eXp.WYSIWYG.createDelRowButton = function(rownum) {
	
	myDelRowButton = document.createElement("img");
	
	myDelRowButton.setAttribute("src", eXp.ICON_RELATIVE + "delete.gif");
	myDelRowButton.setAttribute("class", "clickable");
	
	if (document.all) {
		myDelRowButton.attachEvent('onclick',function() {
			eXp.WYSIWYG.deleteRow(rownum);
		});
	} else {
		myDelRowButton.setAttribute("onclick","eXp.WYSIWYG.deleteRow("+rownum+")");
	}
	
	return myDelRowButton;
}



eXp.WYSIWYG.createButton = function(icon, rownum, pos) {
	
	myButton = document.createElement("img");
	
	if (document.all) {
		myButton.attachEvent('onclick',function() { 
			eXp.WYSIWYG.deleteButton(this, rownum, pos);
		});
		myButton.className = "editorcontrol_toolbarbutton"
	} else {
		myButton.setAttribute("onclick", "eXp.WYSIWYG.deleteButton(this, " + rownum + ", " + pos + ");");
		myButton.setAttribute("class", 'htmleditor_toolbarbutton');
	}
	
	myButton.setAttribute("src", eXp.PATH_RELATIVE + eXp.WYSIWYG_toolboxbuttons[icon][1]);

	return myButton;
}


eXp.WYSIWYG.deleteButton = function(td, rownum, pos) {
	if (confirm(eXp.i18n("Are you sure you want to remove this?"))) {
		eXp.WYSIWYG.enableToolbox(rownum, rows[rownum][pos])
		rows[rownum].splice(pos,1);
		eXp.WYSIWYG.buildToolbar();
	}
}


eXp.WYSIWYG.register = function(icon) {
	rows[g_row].splice(g_pos, 0, icon);
	eXp.WYSIWYG.disableToolbox(icon);
	eXp.WYSIWYG.buildToolbar();
	
}


eXp.WYSIWYG.enableToolbox = function(rownum, key) {
	for (key2 in used) {
		if (used[key2] == key) {
			myButton = document.getElementById("toolboxButton_" + used[key2]);
			
			//ie6 hack
			if (document.all) {
				myButton.className = 'htmleditor_toolboxbutton';
				myButton.attachEvent('onclick', function() { 
					eXp.WYSIWYG.register(event.srcElement.holding);
				});
				myButton.holding = used[key2];
			} else {
				myButton.setAttribute("class", 'htmleditor_toolboxbutton');
				myButton.setAttribute("onclick", "eXp.WYSIWYG.register('" + used[key2] + "')");
			}
			used.splice(key2,1);
		}
	}	
}

eXp.WYSIWYG.disableToolbox = function(icon) {
	if (icon != "space" && icon != "separator") {
		used.push(icon);		

		var myButton = document.getElementById("toolboxButton_" + icon);

		//ie6 hack
		if (document.all) {
			myButton.className = 'editorcontrol_toolboxbutton_selected';
			myButton.attachEvent('onclick', function() { 
				return false;
			});
		} else {
			myButton.setAttribute("class", 'editorcontrol_toolboxbutton_selected');
			myButton.removeAttribute("onclick");
		}
	}
}



//serialize into JS linear array notation
eXp.WYSIWYG.save = function(frm) {
	var saveStr = "[";
	for (i = 0; i < rows.length; i++) {
		if (typeof(rows[i][0]) != "undefined") {
			saveStr += "['";
			for (j = 0; j < rows[i].length; j++) {
				saveStr += rows[i][j];
				if (j != rows[i].length-1) {
					saveStr+="', '";
				}
			}
			saveStr += "']";
			if (i != rows.length - 1) {
				saveStr += ", ";
			}
		}
	}
	saveStr += "]";
	
	input = document.getElementById("config_htmlarea");
	input.setAttribute("value", saveStr);
	frm.submit();
}

// used to build a toolbox of available buttons, the array eXp.WYSIWYG_toolbar in /external/editors/<currenteditor>_toolbar.js has to be maintened manually(for now)
eXp.WYSIWYG.buildToolbox = function(Buttons) {
	myButtonPanel = document.getElementById("editorcontrol_toolbox");
	
	for (currButton in Buttons) {
		myButton  = document.createElement("img");
		
		// difference between internal name and displayed name is possible because of i18n 
		myButton.setAttribute("src", eXp.PATH_RELATIVE + Buttons[currButton][1]);
		myButton.setAttribute("title", Buttons[currButton][0]);
		myButton.setAttribute("alt", currButton);
		myButton.setAttribute("id", "toolboxButton_" + currButton);
		
		//ie6 hack
		if (document.all) {
			myButton.className = "editorcontrol_toolboxbutton";
			myButton.attachEvent('onclick', function() { 
				eXp.WYSIWYG.register(event.srcElement.holding);
			});
			myButton.holding = currButton;
		} else {
			myButton.setAttribute("class", 'editorcontrol_toolboxbutton');
			myButton.setAttribute("onclick", "eXp.WYSIWYG.register('" + currButton + "')");
		}
				
		myButtonPanel.appendChild(myButton);
		
	}
}

eXp.WYSIWYG.buildToolbar = function() {
	var myToolbar = document.getElementById("editorcontrol_toolbar");
	eXp.WYSIWYG.recurseClear(myToolbar);
	
	for (rownum in rows) {
		myRow = document.createElement("div");
		myRow.setAttribute("id", "row" + rownum);
		
		if(document.all) {
			myRow.className = "clearfloat";
		} else {
			myRow.setAttribute("class", "clearfloat");
		}
		
		
		//first initial cursor on a row
		myRow.appendChild(eXp.WYSIWYG.createCursor(rownum, 0));
		
		for (itemkey in rows[rownum]) {
			myRow.appendChild(eXp.WYSIWYG.createButton(rows[rownum][itemkey], rownum, parseInt(itemkey)));
			//increment position number by one because the insert point is after the icon
			myRow.appendChild(eXp.WYSIWYG.createCursor(rownum, parseInt(itemkey) + 1));
		}		
		myRow.appendChild(eXp.WYSIWYG.createDelRowButton(rownum));
		
		myToolbar.appendChild(myRow);
	}	
}
