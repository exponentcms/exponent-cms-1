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
<tr>
	<td style="background-image: url({$smarty.const.THEME_RELATIVE}images/title_bg.gif); padding: 10px;" height="25">
	<img src="{$smarty.const.THEME_RELATIVE}images/doublearrow.gif" alt="" align="middle" hspace="5"/>
{assign var=i value=0}
{foreach from=$sections item=section}
{if $current->parents[$i] == $section->id || $current->id == $section->id}
{math equation="x+1" x=$i assign=i}
{if $section->active == 1}
<a class="mngmntlink navigation_mngmntlink navlink" href="{$section->link}">{$section->name}</a>&nbsp;
{else}
<span class="navlink">{$section->name}</span>&nbsp;
{/if}
{if $section->id != $current->id}
	<img src="{$smarty.const.THEME_RELATIVE}images/slash.gif" alt="" align="middle" hspace="5" />
{/if}
{/if}
{/foreach}
	</td>
</tr>