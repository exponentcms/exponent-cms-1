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

class mimetype {
	function form($object) {
		pathos_lang_loadDictionary('standard','core');
		pathos_lang_loadDictionary('modules','filemanager');
		
		$form = new form();
		if (!isset($object->mimetype)) {
			$object->mimetype = '';
			$object->name = '';
			$object->icon = '';
		} else {
			$form->meta('oldmime',$object->mimetype);
		}
		$form->register('mimetype',TR_FILEMANAGER_MIMETYPE, new textcontrol($object->mimetype));
		$form->register('name',TR_FILEMANAGER_FILETYPENAME,new textcontrol($object->name));
		
		$icodir = MIMEICON_RELATIVE;
		$htmlimg = ($object->icon == '' ? '' : "<img src='themes/".DISPLAY_THEME."/mimetypes/".$object->icon."'/>");
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
		$html .= TR_FILEMANAGER_CHANGEICON;
		$html .= '</a>';

		$form->register('icon',TR_FILEMANAGER_ICON, new customcontrol($html));
		$form->register('submit','',new buttongroupcontrol(TR_CORE_SAVE,'',TR_CORE_CANCEL));
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