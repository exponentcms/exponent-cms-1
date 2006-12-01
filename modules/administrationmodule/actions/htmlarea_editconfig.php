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

if (exponent_permissions_check('htmlarea',exponent_core_makeLocation('administrationmodule'))) {
	$config = $db->selectObject('toolbar_' . SITE_WYSIWYG_EDITOR, "id=".intval($_GET['id']));

?>
<style type="text/css">
	.htmleditor_toolboxbutton:hover {
		border : 2px red solid;
	}
	.htmleditor_toolboxbutton_selected {
		background-color : grey;
	}
</style>
	
	
<script type="text/javascript" src="<?php echo PATH_RELATIVE; ?>js/HTMLAreaToolbarBuilder.js"></script>
<script type="text/javascript" src="<?php echo PATH_RELATIVE; ?>external/editors/<?php echo SITE_WYSIWYG_EDITOR; ?>_toolbox.js"></script>
	

<table cellspacing="0" cellpadding="2" border="0">
	<tbody>
		<tr id="htmleditor_toolbox" />
		<tr>
			<td style="font-size: 12px; font-style: italic;" id="msgTD"></td>
		</tr>
	</tbody>
</table>
<hr size="1" />
<a class="mngmntlink administration_mngmntlink" href="#" onclick="newRow(); return false">New Row</a>
<hr size="1" />
<table cellpadding="2" cellspacing="2" rules="all" border="0">
	<tbody id="toolbar_workspace" />
</table>

<script type="text/javascript">
	var imagePrefix = "";
	// populate the button panel
	exponentJSbuildHTMLEditorButtonSelector(eXp.WYSIWYG_toolboxbuttons);
		
<?php
	
if ($config == null) {
?>
	// 3 initial rows.
	rows.push(new Array());
	rowlens.push(0);
	rows.push(new Array());
	rowlens.push(0);
	rows.push(new Array());
	rowlens.push(0);
<?php
} else {
?>	

	eXp.WYSIWYG_toolbar = <?php echo $config->data; ?>;
	
	for(currRow = 0; currRow < eXp.WYSIWYG_toolbar.length; currRow++) {
		rows.push(new Array());
		rowlens.push(0);
		
		for(currButton = 0; currButton < eXp.WYSIWYG_toolbar[currRow].length; currButton++) {
			//TODO: decide whether to disallow empty rows altoghether -> htmlareatoolbarbuilder.js->save()
			if (eXp.WYSIWYG_toolbar[currRow][currButton] != "") {
				rows[currRow].push(eXp.WYSIWYG_toolbar[currRow][currButton]);
				disableToolbox(eXp.WYSIWYG_toolbar[currRow][currButton]);
			}
		}
	}
<?php
}
?>

	regenerateTable();
</script>
<br />
<hr size="1" />
<form method="post">
<input type="hidden" name="module" value="administrationmodule"/>
<input type="hidden" name="action" value="htmlarea_saveconfig"/>
<?php if (isset($config->id)) { ?>
	<input type="hidden" name="id" value="<?php echo $config->id; ?>"/>
<?php } ?>
<input type="hidden" name="config" value="" id="config_htmlarea" />
Configuration Name:<br /><input type="text" name="config_name" value="<?php echo (isset($config->name) ? $config->name : ""); ?>" /><br />
<input type="checkbox" name="config_activate" <?php echo (isset($config->active) && $config->active == 1 ? 'checked="checked" ' : '');?>/> Activate?<br />

<input type="submit" value="Save" onclick="save(this.form); return false">
</form>

	<?php

} else {
	echo SITE_403_HTML;
}

?>
