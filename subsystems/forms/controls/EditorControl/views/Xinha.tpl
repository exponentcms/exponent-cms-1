{*
##################################################
#
# Copyright (c) 2005-2007  Maxim Mueller
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
	{include file="_header.inc" toolbar="`$view->toolbar`"}

{if $smarty.const.SITE_WYSIWYG_INIT != 1}
	<script type="text/javascript">
	/* <![CDATA[ */
{literal}

		_editor_url = "{/literal}{$view->path_to_editor}{literal}";
		//eXp's new lang naming semantics are incompatible to pretty much everything, translation functions are needed
		//_editor_lang = "{/literal}{$smarty.const.LANG}{literal}";
		_editor_lang = "en";
			
		//list of the id[0], toolbar[1] and plugins[2] for the individual xinha editors
		eXp.WYSIWYG.editordata = new Array();
		
		//list of plugins used by all xinha editors
		eXp.WYSIWYG.plugins = new Array();
		
				
		// get the plugins used in this toolbar
		eXp.WYSIWYG.getPlugins = function (myToolbar) {

			plugins = new Array();
				
			for(currRow = 0; currRow < myToolbar.length; currRow++) {
				for(currButton = 0; currButton < myToolbar[currRow].length; currButton++) {
					currItem = myToolbar[currRow][currButton];
					// plugin required ?
					if(eXp.WYSIWYG.toolbox[currItem][2] != "") {
						// goes into per xinha editor plugin list
						plugins.push(eXp.WYSIWYG.toolbox[currItem][2]);
						
						//goes into global plugin list
						eXp.WYSIWYG.plugins.push(eXp.WYSIWYG.toolbox[currItem][2]);
					}
				}
				//FJD - added to force a linebreak for our defined rows
				eXp.WYSIWYG.toolbar[currRow][myToolbar[currRow].length] = "linebreak";
			}
			return plugins;	
		}


		//callback function on page load
		eXp.WYSIWYG.xinha_init = function () {

			// THIS BIT OF JAVASCRIPT LOADS THE PLUGINS, NO TOUCHING  :)
      			if(!Xinha.loadPlugins(eXp.WYSIWYG.plugins, eXp.WYSIWYG.xinha_init)) return;

			eXp.WYSIWYG.config = new Xinha.Config();

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

			editor_ids = new Array();
			for (i = 0; i < eXp.WYSIWYG.editordata.length; i++) {
				editor_ids.push(eXp.WYSIWYG.editordata[i][0])
			}

			eXp.WYSIWYG.editors = Xinha.makeEditors(editor_ids, eXp.WYSIWYG.config);
				
			//per xinha instance inits override the default settings
			for (i = 0; i < eXp.WYSIWYG.editordata.length; i++) {
				
				//is there is a toolbar ? otherwise don't touch the defaults
				if (eXp.WYSIWYG.editordata[i][1]) {
					//load the plugins for this instance and it's toolbar
					eXp.WYSIWYG.editors[eXp.WYSIWYG.editordata[i][0]].registerPlugins(eXp.WYSIWYG.editordata[i][2], eXp.WYSIWYG.editordata[i][0]);

					//load the toolbar
					eXp.WYSIWYG.editors[eXp.WYSIWYG.editordata[i][0]].config.toolbar = eXp.WYSIWYG.editordata[i][1];
				}
			}
			
			
			//display the whole stuff
			Xinha.startEditors(eXp.WYSIWYG.editors);
		}
			
		//register the callback function
		eXp.register(eXp.WYSIWYG.xinha_init);
{/literal}
	/* ]]> */
	</script>
{/if}
	
	<script type="text/javascript" src="{$view->path_to_editor}XinhaCore.js"></script>
	
	<script type="text/javascript">
	/* <![CDATA[ */
		//eXp.WYSIWYG.getPlugins() also adds rowbreaks to the
		// current toolbar where needed, so execute it first
		plugins = eXp.WYSIWYG.getPlugins(eXp.WYSIWYG.toolbar);

		//register the new textarea to become a Xinha editor, assign it's toolbar and it's plugins
		eXp.WYSIWYG.editordata.push(["{$content->name}", eXp.WYSIWYG.toolbar, plugins]);
	/* ]]> */
	</script>
	
	<textarea id="{$content->name}" name="{$content->name}">{$content->value}</textarea>
</div>
