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
<table>
<tr><td width="22" valign="top">
	<!-- {$file->mimetype} -->
	{getfileicon id=$item->file_id}
</td>
<td>
	<b>{$item->name}</b><br />
	<br />
	<a class="mngmntlink resources_mngmntlink" href="{getfilename id=$item->file_id}">Download</a>
</td></tr>
</table>
<div style="padding-left: 20px;">
{$item->description}
</div>