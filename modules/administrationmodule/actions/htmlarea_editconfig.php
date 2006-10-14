<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Copyright (c) 2006 Maxim Mueller
# Written and Designed by James Hunt
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################
//GREP:HARDCODEDTEXT
//GREP:VIEWIFY
//GREP:REIMPLEMENT

// Part of the HTMLArea category

if (!defined("EXPONENT")) exit("");

if (exponent_permissions_check('htmlarea',exponent_core_makeLocation('AdministrationModule'))) {
	$config = $db->selectObject('toolbar_' . SITE_WYSIWYG_EDITOR, "id=".intval($_GET['id']));

?>
<style type="text/css">

	.editorcontrol_toolboxbutton:hover {
		border : 2px red solid;
	}
	.editorcontrol_toolboxbutton_selected {
		background-color : grey;
	}
	
	.editorcontrol_toolbarbutton {
		clear:both;
	}
	
	.editorcontrol_cursor {
		float:left;
		border:1px black solid;
		background-color : white;
		width:5px;
		height:20px;
	}
	.editorcontrol_cursor_selected {
		float:left;
		background-color : blue;
		width:5px;
		height:20px;
	}
</style>
	
<script type="text/javascript">
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

//initialize namespace
eXp.WYSIWYG = new Object();	

//TODO: move to separate file
eXp.I18N = {
};

//TODO: move to exponent.js.php
eXp.i18n = function(i18n_string) {
	if(eXp.I18N[i18n_string]) {
		i18n_string = eXp.I18N[i18n_string];
	}
	return i18n_string;
}


var used = new Array();
var rows = new Array();

var g_row = 0;
var g_pos = 0;

var lastCursor = null;


eXp.WYSIWYG.newRow = function() {
	rows.push(new Array());
	g_pos = 0;
	g_row = rows.length - 1;
	eXp.WYSIWYG.buildToolbar();
}

eXp.WYSIWYG.recurseClear = function(elem) {
	while (elem.childNodes.length) {
		elem.removeChild(elem.firstChild);
	}
}

eXp.WYSIWYG.buildToolbar = function() {
	var myToolbar = document.getElementById("editorcontrol_toolbar");
	eXp.WYSIWYG.recurseClear(myToolbar);
	
	for (rownum in rows) {
		var myRow = document.createElement("div");
		myRow.setAttribute("id", "row" + rownum);
		
		myRow.appendChild(eXp.WYSIWYG.createCursor(rownum, 0));
		
		for (itemkey in rows[rownum]) {
			myRow.appendChild(eXp.WYSIWYG.createButton(rows[rownum][itemkey], rownum, parseInt(itemkey)));
			myRow.appendChild(eXp.WYSIWYG.createCursor(rownum, parseInt(itemkey)));
		}
				
		myRow.appendChild(eXp.WYSIWYG.createDelRowButton(rownum));
		
		myToolbar.appendChild(myRow);
	}
	
}


eXp.WYSIWYG.createCursor = function(rownum, pos) {
	
	myCursor = document.createElement("div");
	
	//ie6 hack
	if (document.all) {
		myCursor.attachEvent('onclick',function() {
			eXp.WYSIWYG.selectCursor(event.srcElement,rownum,pos);
		});
		myCursor.className = "editorcontrol_cursor";
	} else {
		myCursor.setAttribute("onClick","eXp.WYSIWYG.selectCursor(this,"+rownum+","+pos+"); return false;");
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
		//in case this is our first cursor
		if(lastCursor) {
			lastCursor.className = "editorcontrol_cursor";
		}
		myCursor.className = "editorcontrol_cursor_selected";
	} else {
		//in case this is our first cursor
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
		myButton.setAttribute("onclick","eXp.WYSIWYG.deleteButton(this,"+rownum+","+pos+"); return false;");
		myButton.setAttribute("class", 'htmleditor_toolbarbutton');
	}
	
	myButton.setAttribute("src", eXp.WYSIWYG.toolbox[icon][1]);

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
	g_pos++;
	eXp.WYSIWYG.disableToolbox(icon);
	eXp.WYSIWYG.buildToolbar();
	
}


eXp.WYSIWYG.enableToolbox = function(rownum, key) {
	//for (key in rows[rownum]) {
		// clear used
		for (key2 in used) {
			if (used[key2] == key) {
				myButton = document.getElementById("toolboxButton_"+used[key2]);
				
				//ie6 hack
				if (document.all) {
					myButton.className = 'htmleditor_toolboxbutton';
					myButton.attachEvent('onclick', function() { 
						eXp.WYSIWYG.register('"+used[key2]+"');
					});
				} else {
					myButton.setAttribute("class", 'htmleditor_toolboxbutton');
					myButton.setAttribute("onclick","eXp.WYSIWYG.register('"+used[key2]+"')");
				}
				used.splice(key2,1);
			}
		}
	//}	
}

eXp.WYSIWYG.disableToolbox = function(icon) {
	if (icon != "space" && icon != "separator") {
		used.push(icon);		

		var myButton = document.getElementById("toolboxButton_"+icon);

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

eXp.WYSIWYG.deleteRow = function(rownum) {
	for (key in rows[rownum]) {
		for (key2 in used) {
			if (used[key2] == rows[rownum][key]) {
				var myButton = document.getElementById("toolboxButton_"+used[key2]);
				
				//ie6 hack
				if (document.all) {
					myButton.className = "editorcontrol_toolboxbutton";
					myButton.attachEvent('onclick', function() { 
						eXp.WYSIWYG.register('"+used[key2]+"');
					});
				} else {
					myButton.setAttribute("class", 'editorcontrol_toolboxbutton');
					myButton.setAttribute("onclick","eXp.WYSIWYG.register('"+used[key2]+"')");
				}
				used.splice(key2,1);
			}
		}
	}
	rows.splice(rownum,1);

	g_pos = 0;
	g_row = 0;
	eXp.WYSIWYG.buildToolbar();
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
		myButton.setAttribute("src", Buttons[currButton][1]);
		myButton.setAttribute("title", Buttons[currButton][0]);
		myButton.setAttribute("alt", currButton);
		myButton.setAttribute("id", "toolboxButton_" + currButton);
		myButton.setAttribute("onclick","eXp.WYSIWYG.register('" + currButton + "')");
		myButton.setAttribute('class' , 'editorcontrol_toolboxbutton');
		
		myButtonPanel.appendChild(myButton);
		
	}
}	
</script>

<script type="text/javascript" src="<?php echo PATH_RELATIVE; ?>subsystems/forms/controls/EditorControl/js/<?php echo SITE_WYSIWYG_EDITOR; ?>_toolbox.js"></script>

<div>
	<div id="editorcontrol_toolbox"></div>
	<div id="msgTD"></div>
</div>
<hr/>
<a class="mngmntlink administration_mngmntlink" href="#" onclick="eXp.WYSIWYG.newRow(); return false">{$_TR.create}</a>
<hr/>
<div id="editorcontrol_toolbar"></div>

<script type="text/javascript">
	// populate the button panel
	eXp.WYSIWYG.buildToolbox(eXp.WYSIWYG.toolbox);
		
<?php	
	if ($config != null) {
?>
	eXp.WYSIWYG.toolbar = <?php echo $config->data; ?>;
	
	for(currRow = 0; currRow < eXp.WYSIWYG.toolbar.length; currRow++) {
		rows.push(new Array());
		
		for(currButton = 0; currButton < eXp.WYSIWYG.toolbar[currRow].length; currButton++) {
			//TODO: decide whether to disallow empty rows altoghether -> htmlareatoolbarbuilder.js->save()
			if (eXp.WYSIWYG.toolbar[currRow][currButton] != "") {
				rows[currRow].push(eXp.WYSIWYG.toolbar[currRow][currButton]);
				eXp.WYSIWYG.disableToolbox(eXp.WYSIWYG.toolbar[currRow][currButton]);
			}
		}
	}
	
<?php
	}
?>
	eXp.WYSIWYG.buildToolbar();
</script>
<br />
<hr size="1" />
<form method="post">
<input type="hidden" name="module" value="AdministrationModule"/>
<input type="hidden" name="action" value="htmlarea_saveconfig"/>
<?php if ($config->id) { ?><input type="hidden" name="id" value="<?php echo $config->id; ?>"/><?php } ?>
<input type="hidden" name="config" value="" id="config_htmlarea" />
{$_TR.item_name}:<br /><input type="text" name="config_name" value="<?php echo $config->name ?>" /><br />
<input type="checkbox" name="config_activate" <?php echo ($config->active == 1 ? 'checked="checked" ' : '');?>/> {$_TR.activate}?<br />

<input type="submit" value="Save" onclick="eXp.WYSIWYG.save(this.form); return false">
</form>

<?php
} else {
	echo SITE_403_HTML;
}
?>
