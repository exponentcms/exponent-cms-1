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
<div class="form_title">Data Importers</div>
<div class="form_header">This page lists all installed importers that Exponent recognizes, gives some information about each
<br /><br />
To install a new data importer, use the <a class="mngmntlink administration_mngmntlink" href="{link action=upload_extension module=administrationmodule}">Extension Upload</a> form.</div>
<table cellpadding="4" cellspacing="0" border="0" width="100%">
	{foreach from=$importers item=importer key=impname}
	<tr>
		<td class="administration_modmgrheader"><b>{$importer.name}</b> by {$importer.author}</td>
	</tr>
	<tr>
		<td class="administration_modmgrbody">
			{$importer.description}
			<hr size='1'/>
			<a class="mngmntlink administration_mngmntlink" href="{link module=importer action=page page=start importer=$impname}">Run</a> Data Importer.
		</td>
	</tr>
	<tr><td></td></tr>
	{foreachelse}
	<tr><td align="center"><i>No importers are installed.</i></td></tr>
	{/foreach}
</table>