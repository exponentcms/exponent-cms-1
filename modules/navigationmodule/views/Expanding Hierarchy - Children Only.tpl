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

<div class="navigationmodule expanding-hierarchy-children-only">
<ul>
{foreach from=$sections item=section}
{if $section->numParents != 0}
{assign var=commonParent value=0}
{assign var=isParent value=0}
{foreach from=$current->parents item=parentId}
  	{if $parentId == $section->id}
    		{assign var=isParent value=1}
  	{/if}
	{if $parentId == $section->id || $parentId == $section->parent}
		{assign var=commonParent value=1}
	{/if}
{/foreach}
{if $section->numParents == 0 || $commonParent || $section->id == $current->id ||  $section->parent == $current->id}
    {if $section->numParents == 1 && $isParent == 0 && $current->id != $section->id}
      <li class="depth{$section->depth}">
    {elseif $section->numParents == 1}
      <li class="depth{$section->depth}">
    {else}
      <li class="depth{$section->depth}">
    {/if}
    
    {if $section->active == 1 && $section->id == $current->id}
      <a href="{$section->link}" class="active" {if $section->new_window} target="_blank"{/if}>{$section->name}</a>&nbsp;
    {elseif $section->active == 1}
      <a href="{$section->link}" {if $section->new_window} target="_blank"{/if}>{$section->name}</a>&nbsp;
    {else}
      <span class="side_link">{$section->name}</span>&nbsp;
    {/if}
    </li>
{/if}
{/if}
{/foreach}
</ul>
</div>
