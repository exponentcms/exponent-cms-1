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
{if $nofiles == 1}
No files to copy.  If you hit refresh, this is normal.
{else}
<table cellpadding="0" cellspacing="0" border="0" width="100%">
{foreach from=$success item=status key=file}
<tr>
	<td>{$file}</td>
	<td>
		{if $status == 1}
		<span style="color: green">Copied</span>
		{else}
		<span style="color: red">Failed</span>
		{/if}
	</td>
</tr>
{/foreach}
</table>
<a class="mngmntlink administration_mngmntlink" href="{$redirect}">Back</a>
{/if}