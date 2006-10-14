{*
##################################################
#
# Copyright (c) 2005-2006  Maxim Mueller
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

#This glue file is included by subsystems/forms/controls/htmleditorcontrol.php
#it provides the code for the htmleditorcontrol class' controltoHTML() method 
# it's based on James Hunt's code for that original class
*}
{* the header include contains the starting <div> tag*}	
	{include file="_header.inc" toolbar="`$dm->toolbar`"}
	<textarea id="{$dm->name}" name="{$dm->name}">{$dm->content}</textarea>

	<script type="text/javascript">
	/* <![CDATA[ */
		_editor_url = "{$dm->path_to_editor}";
		//eXp's new lang naming semantics are incompatible to pretty much everything, translation functions are needed
		//_editor_lang = "{$smarty.const.LANG}";
	/* ]]> */
	</script>
	
	<script type="text/javascript" src="{$dm->path_to_editor}htmlarea.js"></script>
	
	<script type="text/javascript">
	/* <![CDATA[ */
{IF $dm->toolbar != NULL}	
	// if plugins are needed, set them up
	eXp.WYSIWYG.setupPlugins = function (myToolbar) {
		plugins = new Array();
		
		for(currRow = 0; currRow < myToolbar.length; currRow++) {
			for(currButton = 0; currButton < myToolbar[currRow].length; currButton++) {
				currItem = myToolbar[currRow][currButton];
				// plugin required ?
				if(eXp.WYSIWYG.toolbox[currItem][2] != "") {
					//the third column of the toolbox array contains the plugin's name
					plugins.push(eXp.WYSIWYG.toolbox[currItem][2]);
				}
			}
		}
			
		//execute plugin loader 
		for(currPlugin = 0; currPlugin < plugins.length; currPlugin++) {
			HTMLArea.loadPlugin(plugins[currPlugin]);
		}
	}
{/IF}
		
	// Setup the basic HTMLArea environment
	HTMLArea.loadPlugin("ContextMenu");
	HTMLArea.loadPlugin("Template");
		
	eXp.WYSIWYG.config = new HTMLArea.Config();
		
	eXp.WYSIWYG.config.debug = false;

	//redirect Image&Link browsers to E's connector
	eXp.WYSIWYG.config.URIs = {
		"blank": "blank.html",
		"link": "../../connector/link.php",
		"insert_image": "../../connector/insert_image.php",
		"insert_table": "insert_table.html",
		"select_color": "select_color.html",
		"about": "about.html",
		"help": "editor_help.html"
	};

{IF $dm->toolbar != NULL}	
	//if there is a configured toolbar set it up	
	eXp.WYSIWYG.setupPlugins(eXp.WYSIWYG.toolbar);
	//The save format of the toolbar is directly compatible with Xinha/HTMLArea
	eXp.WYSIWYG.config.toolbar = eXp.WYSIWYG.toolbar;
{/IF}
		
	oXinha = new HTMLArea("{$dm->name}");
	oXinha.config = eXp.WYSIWYG.config;
	delete eXp.WYSIWYG;
	/* ]]> */
	</script>
</div>
