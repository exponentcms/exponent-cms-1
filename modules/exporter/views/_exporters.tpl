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
<div class="form_title">Data Exporters</div>
<div class="form_header">This page lists all installed exporters that Exponent recognizes, gives some information about each
<br /><br />
To install a new data exporter, use the <a class="mngmntlink administration_mngmntlink" href="{$linkbase}upload_extension">Extension Upload</a> form.</div>
<table cellpadding="4" cellspacing="0" border="0" width="100%">
	{foreach from=$exporters item=exporter key=impname}
	<tr>
		<td class="administration_modmgrheader"><b>{$exporter.name}</b> by {$exporter.author}</td>
	</tr>
	<tr>
		<td class="administration_modmgrbody">
			{$exporter.description}
			<hr size='1'/>
			<a class="mngmntlink administration_mngmntlink" href="{link module=exporter action=page page=start exporter=$impname}">Run</a> Data Exporter.
		</td>
	</tr>
	<tr><td></td></tr>
	{foreachelse}
	<tr><td align="center"><i>No exporters are installed.</i></td></tr>
	{/foreach}
</table>