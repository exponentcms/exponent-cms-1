{*
 * Copyright (c) 2004-2006 OIC Group, Inc.
 * Written and Designed by Phillip Ball
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

<div class="navigationmodule top-nav">
	<ul>
		
	{assign var=isparent value=0}	
	{foreach from=$sections item=section}
	{if $section->parent == 0}
		
		{if $current->parents[0]!=""}
			{foreach from=$current->parents item=parent}
				{if $parent==$section->id}
					{assign var=isparent value=1}				
				{/if}
			{/foreach}
		{/if}
		
		
		
		{if $section->active == 1}
			<li><a class="navlink {if $section->id==$current->id || $isparent==1}current{/if}" href="{$section->link}"{if $section->new_window} target="_blank"{/if}>{$section->name}</a></li>
		{else}
			<li><span class="navlink">{$section->name}</span></li>
		{/if}
		{/if}
		{assign var=isparent value=0}
	{/foreach}
	{permissions level=$smarty.const.UILEVEL_NORMAL}
	{if $canManage == 1}
		<li><a class="navlink" href="{link action=manage}">{$_TR.manage}</a></li>
	{/if}
	{/permissions}
	</ul>
</div>