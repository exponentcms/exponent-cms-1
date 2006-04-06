{*
 * Copyright (c) 2004-2006 OIC Group, Inc.
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
<div class="moduletitle">Regenerating Search Index</div>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
{foreach from=$mods key=name item=status}
<tr class="row {cycle values=odd_row,even_row}">
	<td>{$name}</td>
	<td>{if $status == 1}<span style="color: green">Regenerated</span>{else}<span style="color: red; font-weight: bold">No Search Support</span>{/if}</td>
</tr>
{/foreach}
</table>