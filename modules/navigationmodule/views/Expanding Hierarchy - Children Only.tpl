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
<table cellpadding="1" cellspacing="3" border="0" width="190" style="margin-top: 20px;">
<tr><td>
{if $smarty.server.SCRIPT_NAME== '/source_selector.php' && $canManage == 1}
<a class="mngmntlink preview_mngmntlink" href="{link action=manage}"><strong>[ manage menu ]</strong></a><br />
{elseif $canManage == 1 }
{permissions level=$smarty.const.UILEVEL_NORMAL}
  <a class="mngmntlink preview_mngmntlink" href="{link action=manage}"><strong>[ manage menu ]</strong></a><br />
{/permissions}
{/if}
</td></tr>
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
      <tr><td style="padding-left: {math equation="x*20+10" x=$section->depth-1}px; border-top: 1px solid #919191;">
    {elseif $section->numParents == 1}
      <tr><td style="padding-left: {math equation="x*20+10" x=$section->depth-1}px; border-top: 1px solid #919191;">
    {else}
      <tr><td style="padding-left: {math equation="x*20+10" x=$section->depth-1}px;">
    {/if}
    {if $section->id == $current->id}
      <img style="border:none;" src="{$smarty.const.ICON_RELATIVE}nav_arrow.gif" alt="" />
    {/if}
    
    {if $section->active == 1 && $section->id == $current->id}
      <a href="{$section->link}" class="side_link_active"{if $section->new_window} target="_blank"{/if}>{$section->name}</a>&nbsp;
    {elseif $section->active == 1}
      <a href="{$section->link}" class="side_link"{if $section->new_window} target="_blank"{/if}>{$section->name}</a>&nbsp;
    {else}
      <span class="side_link">{$section->name}</span>&nbsp;
    {/if}
    </td></tr>
{/if}
{/if}
{/foreach}
</table>
