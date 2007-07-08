{*
 * Copyright (c) 2004-2005 OIC Group, Inc.
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
 *{math equation="x*20" x=$section->depth}
 *}
<div id="menu">
{assign var=sectiondepth value=-1}
{foreach from=$sections item=section}
{if $section->depth > $sectiondepth}

{if $section->depth==0}<ul><li><h2>{else}<ul><li>{/if}
{assign var=sectiondepth value=$section->depth}
{else}
{if $section->depth == $sectiondepth}
{if $section->depth==0}
</li></ul>
<ul><li>
{else}</li><li>{/if}
{if $section->depth==0}<h2>{/if}
{/if}

{if $section->depth < $sectiondepth}
{math equation="x+1" x=$section->depth assign=rangeone}
{assign_adv var='closingloop' value="range($rangeone,$sectiondepth)" }
{foreach from=$closingloop item=close}
</li></ul>
{/foreach}
</li><li>

{if $section->depth==0}<h2>{/if}
{assign var=sectiondepth value=$section->depth}
{/if}
{/if}
{if $section->active == 1}
<a href="{$section->link}" class="navlink"{if $section->new_window} target="_blank"{/if}>{$section->name}</a>
{if $section->depth==0}</h2>{/if}
{else}
<span class="navlink">{$section->name}</span>
{if $section->depth==0}</h2>{/if}
{/if}
{/foreach}
</li></ul>
</div>
