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
<div class="navigationmodule children-only">
	{if $moduletitle}<h2>{$moduletitle}</h2>{/if}
	<ul>
		{foreach from=$sections item=section}
			{if $section->numParents != 0 && ($section->parents[0] == $current->id || $section->parents[0] == $current->parents[0])}
				<li>
					{if $section->active == 1}
						<a href="{$section->link}" class="navlink"{if $section->new_window} target="_blank"{/if}>{$section->name}</a>&nbsp;
					{else}
						<span class="navlink">{$section->name}</span>&nbsp;
					{/if}
				</li>
			{/if}
		{/foreach}
	</ul>
</div>
