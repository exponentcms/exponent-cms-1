{*

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

// Should be moved to the EditorControl or ToolbarItem

*}
{* the header include contains the starting <div> tag*}	
	{include file="../../../../subsystems/forms/controls/EditorControl/views/_header.inc" toolbar="`$toolbar`"}
	
	<script type="text/javascript" src="{$smarty.const.PATH_RELATIVE}/subsystems/forms/controls/EditorControl/js/{$smarty.const.SITE_WYSIWYG_EDITOR}_toolbox.js"></script>	

	<div>
		<div id="editorcontrol_toolbox" class="clearfloat"></div>
		<div id="msgTD"></div>
	</div>
	<div class="clearfloat">
		<hr/>
		<a class="mngmntlink administration_mngmntlink" href="#" onclick="eXp.WYSIWYG.createRow(); return false">{$_TR.create}</a><hr/>
	</div>
	<div id="editorcontrol_toolbar" class="clearfloat"></div>

	<script type="text/javascript">
	/* <![CDATA[ */
		// populate the button panel
		eXp.WYSIWYG.buildToolbox(eXp.WYSIWYG.toolbox);
		
{IF $toolbar != null}		
		for(currRow = 0; currRow < eXp.WYSIWYG.toolbar.length; currRow++) {
			rows.push(new Array());
		
			for(currButton = 0; currButton < eXp.WYSIWYG.toolbar[currRow].length; currButton++) {

				if (eXp.WYSIWYG.toolbar[currRow][currButton] != "") {
					rows[currRow].push(eXp.WYSIWYG.toolbar[currRow][currButton]);
					eXp.WYSIWYG.disableToolbox(eXp.WYSIWYG.toolbar[currRow][currButton]);
				}
			}
		}

{/IF}
		eXp.WYSIWYG.buildToolbar();
	/* ]]> */
	</script>

	<br />
	<div class="clearfloat">
		<hr/>

		<form method="post">
			<input type="hidden" name="module" value="AdministrationModule"/>
			<input type="hidden" name="action" value="htmlarea_saveconfig"/>
{IF $dm->id != null}
			<input type="hidden" name="id" value="{$dm->id}"/>
{/IF}
			<input type="hidden" name="config" value="" id="config_htmlarea" />
			{$_TR.item_name}:<br />
			<input type="text" name="config_name" value="{$dm->name}" /><br />
			<input type="checkbox" name="config_activate" {IF $dm->active == 1}checked="checked"{/IF}/> {$_TR.activate}?<br />

			<input type="submit" value="{$_TR.submit}" onclick="eXp.WYSIWYG.save(this.form); return false">
		</form>
	</div>
</div>

