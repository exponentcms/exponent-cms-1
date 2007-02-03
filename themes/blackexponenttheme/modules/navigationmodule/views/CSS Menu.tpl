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
 * $Id: Marked\040Hierarchy.tpl,v 1.4.2.1 2005/02/22 18:05:02 filetreefrog Exp $
 *}

{assign var=in_action value=0}
{if $smarty.request.module == 'navigationmodule' && $smarty.request.action == 'manage'}
	{assign var=in_action value=1}
{/if}


<div id="menu">
{assign var=sectiondepth value=-1}
{foreach from=$sections item=section}
	{if $section->depth > $sectiondepth}
		<ul><li>
	{assign var=sectiondepth value=$section->depth}
	{elseif $section->depth == $sectiondepth}
		</li><li>
	{else}
	{math equation="x-y" x=$sectiondepth y=$section->depth assign=j}
	{section name=closelist loop=$j}
		</li></ul>
	{/section}
	{assign var=sectiondepth value=$section->depth}
		</li><li>
	{/if}
	{if $section->active == 1}
		<a href="{$section->link}" class="navlink"{if $section->new_window} target="_blank"{/if}>{$section->name}</a>
	{else}
		<span class="navlink">{$section->name}</span>
	{/if}
{/foreach}
</li></ul>
</div>
