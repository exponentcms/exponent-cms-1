{*
 *
 * Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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
<div class="form_title">Backup Current Database</div>
<div class="form_header">
Listed below are all of the tables in your site's database.  Select which tables you wish to backup, and then click the 'Backup Selected' button.  Doing so will generate an EQL file (which you must save) that contains the data in the selected tables.  This file can be used later to restore the database to the current state.
</div>

<!--
<textarea cols="100" rows="30">{$dumped}</textarea>
-->

<script type="text/javascript">
{literal}
function selectAll(checked) {
	elems = document.getElementsByTagName("input");
	for (var key in elems) {
		if (elems[key].type == "checkbox" && elems[key].name.substr(0,7) == "tables[") {
			elems[key].checked = checked;
		}
	}
}

function isOneSelected() {
	elems = document.getElementsByTagName("input");
	for (var key in elems) {
		if (elems[key].type == "checkbox" && elems[key].name.substr(0,7) == "tables[") {
			if (elems[key].checked) return true;
		}
	}
	alert("You must select at least one table to export.");
	return false;
}

{/literal}
</script>

<form method="post" action="?">
<input type="hidden" name="module" value="exporter" />
<input type="hidden" name="action" value="page" />
<input type="hidden" name="exporter" value="eql" />
<input type="hidden" name="page" value="savefile" />

<table cellspacing="0" cellpadding="2">
{section name=tid loop=$tables step=2}
<tr class="row {cycle values=even_row,odd_row}">
	<td>
		<input type="checkbox" name="tables[{$tables[tid]}]" {if $tables[tid] != 'sessionticket'}checked {/if}/>
	</td>
	
	<td>{$tables[tid]}</td>
	
	<td width="12">&nbsp;</td>
	
	{math equation="x+1" x=$smarty.section.tid.index assign=nextid}
	<td>
		{if $tables[$nextid] != ""}<input type="checkbox" name="tables[{$tables[$nextid]}]" {if $tables[$nextid] != 'sessionticket'}checked {/if}/>{/if}
	</td>
	
	<td>{$tables[$nextid]}</td>
</tr>
{/section}
<tr>
	<td colspan="2">
		<a href="#" onClick="selectAll(true); return false">Select All</a>
	</td>
	<td colspan="2">
		<a href="#" onClick="selectAll(false); return false">Deselect All</a>
	</td>
</tr>
<tr>
	<td colspan="4">
		<input type="submit" value="Backup Selected" onClick="return true; return isOneSelected();" />
	</td>
</tr>
</table>