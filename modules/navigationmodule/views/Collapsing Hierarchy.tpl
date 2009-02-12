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
<div class="navigationmodule collapsing">
    <ul>
    {foreach from=$sections item=section}
    {assign var=inPath value=0}
        {foreach from=$current->parents item=parentId}
            {if $parentId == $section->id}
                {assign var=inPath value=1}
            {/if}
        {/foreach}
        {if $section->numParents == 0 || $inPath || $section->id == $current->id ||  $section->parent == $current->id}
            <li class="depth{$section->depth} {if $section->id == $current->id}current{/if}">
                {if $section->active == 1}
                    <a href="{$section->link}" class="navlink"{if $section->new_window} target="_blank"{/if}>{$section->name}</a>&nbsp;
                {else}
                    <span class="navlink">{$section->name}</span>&nbsp;
                {/if}
            </li>
        {/if}
    {/foreach}
    </ul>
</div>