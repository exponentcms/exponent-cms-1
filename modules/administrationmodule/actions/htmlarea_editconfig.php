<?php

##################################################
#
# Copyright 2004 James Hunt and OIC Group, Inc.
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# Exponent is distributed in the hope that it
# will be useful, but WITHOUT ANY WARRANTY;
# without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR
# PURPOSE.  See the GNU General Public License
# for more details.
#
# You should have received a copy of the GNU
# General Public License along with Exponent; if
# not, write to:
#
# Free Software Foundation, Inc.,
# 59 Temple Place,
# Suite 330,
# Boston, MA 02111-1307  USA
#
# $Id$
##################################################
//GREP:HARDCODEDTEXT
//GREP:VIEWIFY
//GREP:REIMPLEMENT

// Part of the HTMLArea category

if (!defined("PATHOS")) exit("");

if (pathos_permissions_check('htmlarea',pathos_core_makeLocation('administrationmodule'))) {

	$imagedir = BASE."external/htmlarea/toolbaricons";
	$imagebase = PATH_RELATIVE."external/htmlarea/toolbaricons";
	$confdir = BASE."external/htmlarea";

	$config = $db->selectObject("htmlareatoolbar","id=".$_GET['id']);

	?>
	<script type="text/javascript" src="<?php echo PATH_RELATIVE; ?>js/HTMLAreaToolbarBuilder.js"></script>
	<script type="text/javascript">
	var imagePrefix = "<?php echo $imagebase."/"; ?>";
	</script>
<?php
	$iconconfig = "";
	if (is_readable(THEME_ABSOLUTE."toolbaricons.conf.php")) {
		$iconconfig = include($confdir."/toolbaricons.conf.php");
	} else if (is_readable($confdir . "/toolbaricons.conf.php")) {
		$iconconfig = include($confdir."/toolbaricons.conf.php");
	}
	else {
		echo "Toolbar Icon Configuration file not found";
		return;
	}
	
?>
<table cellspacing="0" cellpadding="2" border="0">
<tr>
<?php

foreach ($iconconfig as $row) {
	foreach ($row as $icondata) {
		$icon = $icondata['icon'];
		$span = (isset($icondata['span']) ? ($icondata['span']) : 1);
		$tooltip = (isset($icondata['tooltip']) ? $icondata['tooltip'] : "");
		
		$file = $icon . ".gif";
		echo "<td colspan='$span' id='td_$icon' onmouseover='this.style.background=\"red\";' onmouseout='this.style.background=\"white\"'>";
		echo "<a id='a_$icon' href='#' onClick='register(\"$icon\")'>";
		echo "<img id='img_$icon' src='$imagebase/$file' border='0' alt='" . $tooltip . "' title='" . $tooltip . "' />";
		echo '</a>';
		echo "</td>";
	}
	echo "</tr><tr>";
}

?>
</tr>
<tr><td colspan="<?php echo $perrow; ?>" style="font-size: 12px; font-style: italic;" id="msgTD"></td></tr>
</table>
<hr size="1" />
<a class="mngmntlink administration_mngmntlink" href="#" onclick="newRow(); return false">New Row</a>
<hr size="1" />
<table cellpadding="2" cellspacing="2" rules="all" style="border: 1px dashed lightgrey">
<tbody id="toolbar_workspace">
<tbody>
</table>
<script type="text/javascript">
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
	$data = unserialize($config->data);
	$rowcount = 0;
	foreach ($data as $row) {
		?>
rows.push(new Array());
rowlens.push(0);
		<?php
		foreach ($row as $icon) {
			?>
rows[<?php echo $rowcount;?>].push("<?php echo $icon;?>");
rowlens[<?php echo $rowcount;?>] += toolbarIconSpan("<?php echo $icon;?>");
disableToolbox("<?php echo $icon;?>");
			<?php
			
		}
		$rowcount++;
	}
}
?>
regenerateTable();
</script>
<br />
<hr size="1" />
<form method="post">
<input type="hidden" name="module" value="administrationmodule"/>
<input type="hidden" name="action" value="run"/>
<input type="hidden" name="m" value="administrationmodule"/>
<input type="hidden" name="a" value="htmlarea_saveconfig"/>
<?php if ($config->id) { ?><input type="hidden" name="id" value="<?php echo $config->id; ?>"/><?php } ?>
<input type="hidden" name="config" value="" id="config_htmlarea" />
Configuration Name:<br /><input type="text" name="config_name" value="<?php echo $config->name ?>" /><br />
<input type="checkbox" name="config_activate" <?php echo ($config->active == 1 ? "checked " : "");?>/> Activate?<br />

<input type="submit" value="Save" onclick="save(this.form); return false">
</form>

	<?php

} else {
	echo SITE_403_HTML;
}

?>