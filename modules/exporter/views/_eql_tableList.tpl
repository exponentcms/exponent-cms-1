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
<div class="form_title">Export Current Database</div>
<div class="form_header">
Listed below are all of the tables in your site database.  Select which tables you wish to export, and then click the 'Export Data' button.  Doing so will generate an EQL file (which you must save) that contains the data in the selected tables.  This file can be used later to restore the database to the current state.
</div>

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
		<a href="#" onClick="selectAll('tables[',true); return false">Select All</a>
	</td>
	<td></td>
	<td colspan="2">
		<a href="#" onClick="selectAll('tables[',false); return false">Deselect All</a>
	</td>
</tr>
<tr>
	<td colspan="5"><br /></td>
</td>
<tr>
	<td colspan="2" valign="top"><b>File name template:</b></td>
	<td colspan="3">
		<input type="text" name="filename" size="20" value="database" />
	</td>
</tr>
	<td colspan="5">
		<div style="border-top: 1px solid #CCCC;">
			Use __DOMAIN__ for this website's domain name, __DB__ for the site's database name and any strftime options for time specification. The EQL extension will be added for you. Any other text will be preserved.
			<br />
		</div>
	</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
	<td colspan="3">
		<input type="submit" value="Export Data" onClick="{literal}if (isOneSelected('tables[')) { return true; } else { alert('You must select at least one table to export.'); return false; }{/literal}" />
	</td>
</tr>
</table>