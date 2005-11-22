{*
 *
 * Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
 * All Changes as of 6/1/05 Copyright 2005 James Hunt
 *
 * This file is part of Exponent
 *
 * Exponent is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * Exponent is distributed in the hope that it
 * will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR
 * PURPOSE.  See the GNU General Public License
 * for more details.
 *
 * You should have received a copy of the GNU
 * General Public License along with Exponent; if
 * not, write to:
 *
 * Free Software Foundation, Inc.,
 * 59 Temple Place,
 * Suite 330,
 * Boston, MA 02111-1307  USA
 *
 * $Id$
 *}
<div class="form_title">{$_TR.form_title}</div>
<div class="form_header">{$_TR.form_header}</div>
<script type="text/javascript">
{literal}
function mods_selectUnselectAll(setChecked) {
	var elems = document.getElementsByTagName("input")
	for (key = 0; key < elems.length; key++) {
		if (elems[key].type == "checkbox" && elems[key].name.substr(0,5) == "mods[") {
			elems[key].checked = setChecked;
		}
	}
}
{/literal}
</script>
<form method="post" action="">
<input type="hidden" name="module" value="exporter" />
<input type="hidden" name="action" value="page" />
<input type="hidden" name="exporter" value="files" />
<input type="hidden" name="page" value="export" />
<table cellspacing="0" cellpadding="2" border="0">
<tr><td class="header">&nbsp;</td><td class="header">{$_TR.module}</td></tr>
{foreach from=$mods key=modname item=modulename}
<tr>
	<td>
		<input type="checkbox" name="mods[{$modname}]" />
	</td>
	<td>
		{$modulename}
	</td>
</tr>
{/foreach}
<tr><td colspan="2">
<a href="#" onClick="mods_selectUnselectAll(true); return false;">{$_TR.select_all}</a>&nbsp;|&nbsp;<a href="#" onClick="mods_selectUnselectAll(false); return false;">{$_TR.deselect_all}</a>
</td></tr>
<tr>
	<td colspan="2" valign="top"><b>{$_TR.file_template}</b>
		<input type="text" name="filename" size="20" value="files" />
	</td>
</tr>
	<td colspan="2">
		<div style="border-top: 1px solid #CCCC;">{$_TR.template_description}<br /></div>
	</td>
</tr>
</table>
<input type="submit" value="{$_TR.export_files}" />
</form>