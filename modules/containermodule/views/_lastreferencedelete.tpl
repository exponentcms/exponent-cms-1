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
{script unique="deletemodule" yuimodules="container"}
{literal}

YAHOO.util.Event.onDOMReady(function(){
	
	var message = "{/literal}{$_TR.confirm}";{literal}
	YAHOO.namespace("example.container");
	
	// Define various event handlers for Dialog
	var handleYes = function() {
		this.hide();
		document.location = {/literal}"{$redirect}";{literal}
	};
	var handleNo = function() {
		this.hide();
		var textlink = {/literal}"{link m=$iloc->mod s=$iloc->src i=$iloc->int action=delete_content}";{literal}
		document.location = textlink.replace(/&amp;/g,"&");
	};

	// Instantiate the Dialog
	YAHOO.example.container.simpledialog1 = new YAHOO.widget.SimpleDialog("expdialog",
									{ 	width: "400px",
										fixedcenter: true,
										visible: false,
										modal:true,
										draggable: false,
										close: true,
										text: message,
										icon: YAHOO.widget.SimpleDialog.ICON_HELP,
										constraintoviewport: true,
										buttons: [ { text:"Send to Recycle Bin", handler:handleYes, isDefault:true },
											{ text:"Delete Permanently",  handler:handleNo } ]
									} );
	YAHOO.example.container.simpledialog1.setHeader("Send to Recycle Bin?");
	
	// Render the Dialog
	YAHOO.example.container.simpledialog1.render("recycle-dlg");
	YAHOO.example.container.simpledialog1.show();
});

{/literal}
{/script}
<div id="recycle-dlg"></div>
