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
 
<div class="breadcrumb">
<h2>You Are Here:</h2> 
{assign var=i value=0}
{foreach from=$sections item=section}
{if $current->numParents >= $i && ($current->id == $section->id || $current->parents[$i] == $section->id)}
{math equation="x+1" x=$i assign=i}
{if $section->active == 1}
<a href="{$section->link}"{if $section->new_window} target="_blank"{/if}>{$section->name}</a>&nbsp;
{else}
<span class="navlink">{$section->name}</span>&nbsp;
{/if}
{if $section->id != $current->id}<b> > </b>{/if}
{/if}
{/foreach}	
</div>
