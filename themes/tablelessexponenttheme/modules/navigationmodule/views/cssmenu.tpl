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

<div class="navigation" >
	<div id="menu">
	<ul>
			{assign var=isFirst value=1}
			{foreach from=$sections item=section}
				{if $section->depth != $lastSection->depth}
					{if $section->depth < $lastSection->depth}
					{math equation="x-y" x=$lastSection->depth y=$section->depth assign=j}
						</li>
						{section name=closelist loop=$j}
							</ul></li>
						{/section}
					{elseif $section->depth > $lastSection->depth}
							<ul>
					{/if}
				{elseif $section->depth == $lastSection->depth && $isFirst != 1}
					</li>
				{/if}
				{if $section->depth == 0}
					{if $section->active == 1}
						<li class="{$section->name|replace:' ':''}"><a href="{$section->link}" {if $section->new_window} target="_blank"{/if}>{$section->name}</a>
					{elseif $section->name == "Rulebar"}
						<li class="{$section->name}">
					{else}
						<li class="{$section->name|replace:' ':''}"><a href="#" {if $section->new_window} target="_blank"{/if}></a>	
					{/if}
				{elseif $section->depth > 0}
					{if $section->active == 1}
						<li><a href="{$section->link}" {if $section->new_window} target="_blank"{/if} title="{$section->name}">{$section->name}</a>
					{else}
						<li>{$section->name}
					{/if}
				{/if}
				{assign var=lastSection value=$section}
				{assign var=isFirst value=0}
			{/foreach}
			</li>
			{section name=closelist loop=$section->depth}
				</ul></li>
			{/section}
			{permissions level=$smarty.const.UILEVEL_NORMAL}
				{if $canManage == 1}
					<li class="Manage"><a href="{link module=navigationmodule action=manage}">Manage</a></li>
				{/if}
			{/permissions}
			</ul>
	</div>
</div>
