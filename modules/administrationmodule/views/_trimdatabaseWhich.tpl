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
<div class="form_title">Trim Database</div>
<div class="form_header">Exponent has examined the database and determined which tables are no longer being used.  Please select which ones you want to remove from the database.</div>
<form method="post" action="">
<input type="hidden" name="module" value="administrationmodule" />
<input type="hidden" name="action" value="trimdatabase_final" />
<table cellpadding="2" cellspacing="0" width="100%" border="0">
{foreach from=$droppable_tables item=rowcount key=table}
<tr class="row {cycle values='odd,even'}_row">
<td><input type="checkbox" name="tables[{$table}]" {if $rowcount == 0}checked {/if}/></td>
<td>
{$table}
</td>
<td>
{$rowcount} {plural singular=Record plural=Records count=$rowcount}
</td></tr>
{foreachelse}
<tr><td colspan="3"><b>No unused tables were found.</b></td></tr>
{/foreach}
{if $droppable_count != 0}
<tr><td colspan="3">
<a href="#" onClick="selectAll('tables[',true); return false; ">Select All</a>
&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="#" onClick="selectAll('tables[',false); return false; ">Deselect All</a>
<br />
<input type="submit" value="Delete Selected" onClick="{literal}if (isOneSelected('tables[')) { return true; } else { alert('You must select at least one table.'); return false; }{/literal}" />
</td></tr>
{/if}
</table>