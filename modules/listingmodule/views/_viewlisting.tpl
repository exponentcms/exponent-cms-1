{*
 *
 * Copyright (c) 2004-2005 OIC Group, Inc.
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
 * $Id: _viewlisting.tpl,v 1.3 2005/02/19 16:53:36 filetreefrog Exp $
 *}
 
 {*
<div class="listing_name">{$listing->name}</div>
<br><br>
<div class="listing_body">
{$listing->body}
</div>
*}

<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td width="100" align="left" valign="top"><img style="border:none" src="{$listing->picpath}" alt="" /></td>
	<td align="left" style="font-size:16"><b>&nbsp;&nbsp;{$listing->name}</b></td>
</tr>
<tr>
	<td colspan="2"><br><br>{$listing->body}</td>
</tr>
</table>