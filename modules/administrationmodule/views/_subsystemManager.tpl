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
<div class="form_title">Manage Subsystems</div>
<div class="form_header">This page lists all installed subsystems that Exponent recognizes
<br /><br />
Clicking the 'View Files' link will bring up a list of files that belog to the subsystem, along with file integrity checksums.
<br /><br />
To install a new subsystem, use the <a class="mngmntlink administration_mngmntlink" href="{link action=upload_extension}">Extension Upload</a> form.</div>
<table cellpadding="2" cellspacing="0" border="0" width="100%">
{foreach from=$info key=subsys item=meta}
	<tr>
		<td style="background-color: lightgrey"><b>{$meta.name}</b> by {$meta.author} version {$meta.version}</td>
		<td style="background-color: lightgrey" align="right"><b>{$subsys}</td>
	</tr>
	<tr>
		<td colspan="3" style="padding-left: 10px; border: 1px solid lightgrey;">
			<a class="mngmntlink administration_mngmntlink" href="{link module=info action=showfiles type=$smarty.const.CORE_EXT_SUBSYSTEM name=$subsys}">
				View Files
			</a>
			<hr size="1" />
			{$meta.description}
		</td>
	</tr>
	<tr><td></td></tr>
{/foreach}
</table>