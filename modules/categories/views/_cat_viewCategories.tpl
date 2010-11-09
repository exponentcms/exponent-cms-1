{*
 * Copyright (c) 2004-2006 OIC Group, Inc.
 * Written and Designed by James Hunt
 *
 * Copyright (c) 2007 ACYSOS S.L. Modified by Ignacio Ibeas
 * Added subcategory function
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
<div class="form_title">
	{$_TR.form_title}
</div>
<table cellspacing="0" cellpadding="2" border="0">
{foreach from=$categories item=category}
	<tr><td style="padding-left: {math equation="x*20" x=$category->depth}px">{$category->name}</td>
</tr>
{foreachelse}
<tr>
	<td colspan="2" align="center"><i>{$_TR.no_categories}</i></td>
</tr>
{/foreach}
</table>