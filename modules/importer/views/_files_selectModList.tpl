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

<form method="post" action="">
<input type="hidden" name="module" value="importer" />
<input type="hidden" name="action" value="page" />
<input type="hidden" name="importer" value="files" />
<input type="hidden" name="page" value="extract" />
<input type="hidden" name="dest_dir" value="{$dest_dir}" />

<table cellspacing="0" cellpadding="0" border="0" width="100%">
	{foreach from=$file_data item=mod_data key=modname}
	<tr>
		<td class="header" width="16"><input type="checkbox" checked name="mods[{$modname}]" /></td>
		<td class="header">{if $mod_data[0] != ''}{$mod_data[0]}{else}Unknown Module Type: {$modname}{/if}</td>
	</tr>
		{foreach from=$mod_data[1] item=file}
			<tr class="row {cycle values=even_row,odd_row}"><td></td><td>{$file}</td></tr>
		{/foreach}
	{/foreach}
	<tr><td colspan="2"><input type="submit" value="Process" /></td></tr>
</table>
</form>