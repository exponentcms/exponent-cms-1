{*
 *
 * Copyright 2004 James Hunt and OIC Group, Inc.
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
{foreach from=$dates item=d}
<tr class="row {cycle values='even_row,odd_row'}">
	<td width="10">
		<input type="checkbox" name="dates[{$d->id}]" {if $d->id == $checked_date->id}checked {/if}/>
	</td>
	<td>
		{$d->date|format_date:$smarty.const.DISPLAY_DATE_FORMAT}
	</td>
</tr>
{/foreach}
<tr>
	<td colspan="2">
	{literal}
		<script type="text/javascript">
		function recur_selectUnselectAll(setChecked) {
			var elems = document.getElementsByTagName("input")
			for (var key in elems) {
				if (elems[key].type == "checkbox" && elems[key].name.substr(0,6) == "dates[") {
					elems[key].checked = setChecked;
				}
			}
		}
		</script>
	{/literal}
		<a class="mngmntlink calendar_mngmntlink" href="#" onClick="recur_selectUnselectAll(true); return false;">Select All</a>
		&nbsp;/&nbsp;
		<a class="mngmntlink calendar_mngmntlink" href="#" onClick="recur_selectUnselectAll(false); return false;">Unselect All</a>
	</td>
</tr>