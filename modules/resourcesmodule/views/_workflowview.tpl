{*
 * Copyright (c) 2004-2011 OIC Group, Inc.
 * Written and Designed by James Hunt
 *
 * This file is part of Exponent
 *
 * Exponent is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * GPL: http://www.gnu.org/licenses/gpl.txt
 *
 *}
<table>
<tr><td width="22" valign="top">
	<!-- {$file->mimetype} -->
	{getfileicon id=$item->file_id}
</td>
<td>
	<b>{$item->name}</b><br />
	<br />
	<a class="mngmntlink resources_mngmntlink" href="{getfilename id=$item->file_id}">{$_TR.download}</a>
</td></tr>
</table>
<div style="padding-left: 20px;">
{$item->description}
</div>
