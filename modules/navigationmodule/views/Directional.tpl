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
 * 
 *}
 <div>
{math equation="x-1" x=$current->rank assign="prevrank"}
{if $prevrank < 0}
	&lt; Prev Page
{else}
	{foreach from=$sections item=section}
	{if $section->parent ==$current->parent && $section->rank==$prevrank}
	<a href="{$section->link}">
		&lt; Prev Page
	</a>
	{/if}
	{/foreach}
{/if}

&nbsp;|&nbsp;

{if $current->parent == 0}
	Up
{else}
	<a href="?section={$current->parent}">Up</a>
	&nbsp;|&nbsp;
	<a href="?section={$current->parents[0]}">Top</a>
{/if}

&nbsp;|&nbsp;

{math equation="x+1" x=$current->rank assign="nextrank"}
{assign var=gotlink value=0}
{foreach from=$sections item=section }
{if $section->parent == $current->parent && $section->rank == $nextrank}
<a href="{$section->link}">Next Page &gt;</a>
{assign var=gotlink value=1}
{/if}
{/foreach}
{if $gotlink == 0}
Next Page &gt;
{/if}
</div>