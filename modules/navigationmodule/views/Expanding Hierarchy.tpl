{*
* Copyright (c) 2004-2006 OIC Group, Inc.
* Written and Designed by James Hunt
* Modified by Maia Good
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
{assign var=sectiondepth value=-1}
{assign var=menustarted value=0}
{assign var=titlepresent value=0}
{if $moduletitle != ""}<h2>{$moduletitle}{assign var=titlepresent value=1}</h2>{/if}
{foreach from=$sections item=section}
 {assign var=commonParent value=0}
 {foreach from=$current->parents item=parentId}
  {if $parentId == $section->id || (($parentId == $section->parent) && ($section->depth <= $current->depth))}
   {assign var=commonParent value=1}
  {/if}
 {/foreach}
 {if $section->numParents == 0 || $commonParent || $section->id == $current->id ||  $section->parent == $current->id}
 {if $section->depth > $sectiondepth}
  <ul{if !$menustarted} class="expanding-hierarchy"{assign var=menustarted value=1}{assign var=openlist value=0}{/if}>
  {assign var=sectiondepth value=$section->depth}
  {assign var=openlist value=$openlist+1}
 {elseif $section->depth == $sectiondepth}
  </li>
 {else}
  {math equation="x-y" x=$sectiondepth y=$section->depth assign=j}
  {section name=closelist loop=$j}
   </li></ul>
   {assign var=openlist value=$openlist-1}
  {/section}
  {assign var=sectiondepth value=$section->depth}
  </li>
 {/if}
 {assign var=class value=""}
 {assign var=parent value=0}
 {foreach from=$sections item=iSection}
  {if $iSection->parents[0] == $section->id }
   {assign var=parent value=1}
  {/if}
 {/foreach}
 {if $parent == 1 }{assign var=class value="$class parent"}{/if}
 {if $section->depth != 0 }{assign var=class value="$class child"} {/if}
 {if $section->active != 1}{assign var=class value="$class inactive"}{/if}
 {if $section->active == 1}
  <li class="navl{$section->depth}{$class}">
   <a href="{$section->link}" class="navlink"{if $section->new_window} target="_blank"{/if}>{$section->name}</a>
 {else}
  <li class="{$class}">
   <span class="inactive">{$section->name}</span>
 {/if}
 {/if}
{/foreach}
{section name=finalclose loop=$openlist}
 </li></ul>
{/section}
{permissions level=$smarty.const.UILEVEL_NORMAL}
 {if $canManage == 1}
  <p class="managelink"><a class="navlink" href="{link action=manage}">{$_TR.manage}</a></p>
 {/if}
{/permissions}
