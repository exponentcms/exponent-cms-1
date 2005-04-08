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
<div class="form_title">Select Which Modules</div>
<div class="form_header">
Listed below are the module types that have uploaded files.  Select which type of modules you want to export the files for.
</div>
<form method="post" action="">
<input type="hidden" name="module" value="exporter" />
<input type="hidden" name="action" value="page" />
<input type="hidden" name="exporter" value="files" />
<input type="hidden" name="page" value="export" />
<table cellspacing="0" cellpadding="2" border="0">
<tr><td class="header">&nbsp;</td><td class="header">Module</td></tr>
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
<a href="#" onClick="selectAll('mods[',true); return false;">Select All</a>&nbsp;|&nbsp;<a href="#" onClick="selectAll('mods[',false); return false;">Unselect All</a>
</td></tr>
<tr>
	<td colspan="2" valign="top"><b>File name template:</b>
		<input type="text" name="filename" size="20" value="files" />
	</td>
</tr>
	<td colspan="2">
		<div style="border-top: 1px solid #CCCC;">
			Use __DOMAIN__ for this website's domain name and any strftime options for time specification. The extension will be added for you. Any other text will be preserved.
			<br />
		</div>
	</td>
</tr>
</table>
<input type="submit" onClick="{literal}if (isOneSelected('mods[')) { return true; } else { alert('You must select at least one module to export files for.'); return false; }{/literal}" value="Export" />
</form>