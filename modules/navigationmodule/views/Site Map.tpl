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

<div class="navigationmodule site-map">
	<h1>{$moduletitle}</h1>
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
	</ul>
</div>
