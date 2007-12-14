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
		{foreach from=$sections item=section}
			<li style="margin-left: {math equation="x*20+10" x=$section->depth-1}px;">
				{if $section->active == 1}
					<a title="{$section->description}" href="{$section->link}" {if $section->new_window} target="_blank"{/if}>{$section->name}</a>
				{else}
					<span>{$section->name}</span>&nbsp;
				{/if}
			</li>
		{/foreach}
	</ul>
</div>
