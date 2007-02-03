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
 * $Id: Unordered List.tpl 1242 2006-05-22 00:18:46 -0500 (Mon, 22 May 2006) maxxcorp $
 *}
{assign var=in_action value=0}
{if $smarty.request.module == 'navigationmodule' && $smarty.request.action == 'manage'}
	{assign var=in_action value=1}
{/if}
<div id="menu">
<ul>
{assign var=i value=0}
{foreach from=$sections item=section}
{if $section->depth > $i}
	<ul>
	{math equation="x+1" x=$i assign=i}
{elseif $section->depth < $i}
	{assign var=j value=0}
	{math equation="x-y" x=$i y=$section->depth assign=j}
	{section name=closeloop loop=$j}
		</ul>
		{math equation="x-1" x=$i assign=i}
	{/section}
{/if}
{if $section->active == 1}
	{if $section->id == $current->id}
		<li><a class="currentitem" href="{$section->link}">{$section->name}</a>
		{else}
		<li><a href="{$section->link}">{$section->name}</a>
	{/if}
{else}
</li>
	<li><h6>{$section->name}</h6>
{/if}
{/foreach}
</ul>
</div>
