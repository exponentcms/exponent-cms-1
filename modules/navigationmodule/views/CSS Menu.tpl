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
	<li><a href="{$section->link}">{$section->name}</a>
{else}
	<li><h6>{$section->name}</h6>
{/if}
{/foreach}

{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $canManage == 1}
	{if $in_action == 1}
		<li><span id="current" class="manage">< manage ></span>
	{else}
		<li> <a class="manage" href="{link action=manage}">< manage ></a>
	{/if}
{/if}
{/permissions}
</ul>
</div>
