<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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

class mimetype {
	function form($object) {
		$i18n = exponent_lang_loadFile('datatypes/mimetype.php');
		
		$form = new form();
		if (!isset($object->mimetype)) {
			$object->mimetype = '';
			$object->name = '';
			$object->icon = '';
		} else {
			$form->meta('oldmime',$object->mimetype);
		}
		
		
		
		$form->register('mimetype',$i18n['mimetype'], new textcontrol($object->mimetype));
		$form->register('name',$i18n['name'],new textcontrol($object->name));
		
		$icodir = MIMEICON_RELATIVE;
		$htmlimg = ($object->icon == '' ? '' : '<img src="'.MIMEICON_RELATIVE.$object->icon.'"/>');
		// Replace this with something a little better.
		$html = <<<EOD
<span id="iconSPAN">$htmlimg</span><input type="hidden" id="iconHIDDEN" name="icon" value=""/>
<script type="text/javascript">
var g_span = document.getElementById("iconSPAN");
var g_hidden = document.getElementById("iconHIDDEN");

function setIcon(src) {
	if (g_span.childNodes.length) {
		g_span.removeChild(g_span.childNodes[0])
	}
	var img = g_span.appendChild(document.createElement("img"));
	img.setAttribute("src",src);
	
	g_hidden.setAttribute("value",src);
}
</script>
<a class="mngmntlink" href="" onClick="window.open('iconspopup.php?icodir=$icodir','icons','title=Icons,width=400,height=400'); return false">
EOD;
		$html .= $i18n['changeicon'];
		$html .= '</a>';

		$form->register('icon',$i18n['icon'], new customcontrol($html));
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		return $form;
	}
	
	function update($values,$object) {
		$object->mimetype = $values['mimetype'];
		$object->name = $values['name'];
		$object->icon = basename($_POST['icon']);
		
		return $object;
	}
}

?>