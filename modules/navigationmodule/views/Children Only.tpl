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
 <table cellpadding="1" cellspacing="0" border="0" width="100%">
{foreach from=$sections item=section}
{if $section->numParents != 0 && ($section->parents[0] == $current->parents[0] || $section->parents[0] == $current->id)}
<tr><td style="padding-left: {math equation="x*20-20" x=$section->depth}px">
{if $section->active == 1}
<a href="{$section->link}" class="navlink">{$section->name}</a>&nbsp;
{else}
<span class="navlink">{$section->name}</span>&nbsp;
{/if}
</td></tr>
{/if}
{/foreach}
</table>
{if $canManage == 1}
[ <a class="navlink" href="{link action=manage}">manage</a> ]
{/if}