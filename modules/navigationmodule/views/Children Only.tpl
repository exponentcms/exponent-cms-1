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
{assign var=nolist value=0}
<div class="navigationmodule children-only">
 {if $moduletitle}<h2>{$moduletitle}</h2>{/if}
 {foreach from=$sections item=section}
  {if $current->id == $section->parent}
   {if $nolist == 0}<ul>{assign var=nolist value=1}{/if}
   <li>
   {if $section->active == 1}
    <a href="{$section->link}" class="navlink"{if $section->new_window} target="_blank"{/if}>{$section->name}</a>&nbsp;
   {else}
    <span class="navlink">{$section->name}</span>&nbsp;
   {/if}
   </li>
  {/if}
 {/foreach}
 {if $nolist == 1}</ul>{/if}
</div>
