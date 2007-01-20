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

{if $view->init_done=false}
	<script type="text/javascript">
	/* <![CDATA[ */
{literal}
		//figure out an intelligent way to maintain the status of init_done in PHP, the handle it there
		//best way to avoid additional CONSTANTS would be to have a eXp->WYSIWYG->init_done		
		if(! _editor_url) {	
			_editor_url = "{$view->path_to_editor}";
			//eXp's new lang naming semantics are incompatible to pretty much everything, translation functions are needed
			//_editor_lang = "{$smarty.const.LANG}";
			_editor_lang = "en";
			
			
			//list of the id's of the textareas to become xinha editors
			eXp.WYSIWYG.editor_ids = new Array();
			//a list of per xinha instance toolbars
			eXp.WYSIWYG.toolbars = new Array();
					
			
			// get the plugins used in this toolbar
			eXp.WYSIWYG.setupPlugins = function (myInstanceId, myToolbar) {

				plugins = new Array();
				
				for(currRow = 0; currRow < myToolbar.length; currRow++) {
					for(currButton = 0; currButton < myToolbar[currRow].length; currButton++) {
						currItem = myToolbar[currRow][currButton];
						// plugin required ?
						if(eXp.WYSIWYG.toolbox[currItem][2] != "") {
							plugins.push(eXp.WYSIWYG.toolbox[currItem][2]);
						}
					}
				}
					
				eXp.WYSIWYG.editors[myInstanceId].registerPlugins(plugins);
			}
			
			
			//callback function on page load
			eXp.WYSIWYG.xinha_init = function () {
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
	
				eXp.WYSIWYG.editors = Xinha.makeEditors(eXp.WYSIWYG.editors, eXp.WYSIWYG.config);
				
				//per xinha instance inits override the default settings
				for (i = 0; i < eXp.WYSIWYG.toolbars.length; i++) {
					
					//is there is a toolbar ? otherwise don't touch the defaults
					if (eXp.WYSIWYG.toolbars[i]) {
						//load the plugins for this instance and it's toolbar
						eXp.WYSIWYG.setupPlugins(eXp.WYSIWYG.editor_ids[i], eXp.WYSIWYG.toolbars[i]);
						//load the toolbar
						eXp.WYSIWYG.editors[eXp.WYSIWYG.editor_ids[i]].config.toolbar = eXp.WYSIWYG.toolbars[i];
					}
				}
				
				
				//display the whole stuff
				Xinha.startEditors(eXp.WYSIWYG.editors);
			}
			
			//register the callback function
			eXp.register(xinha_init);
		}
{/literal}
	/* ]]> */
	</script>
{/if}
	
	<script type="text/javascript" src="{$view->path_to_editor}XinhaCore.js"></script>
	
	<script type="text/javascript">
	/* <![CDATA[ */
		//transfer the current toolbar into the list of toolbars
		eXp.WYSIWYG.toolbars.append({$view->toolbar});
	/* ]]> */
	</script>
	
	<textarea id="{$content->name}" name="{$content->name}">{$content->value}</textarea>
</div>
