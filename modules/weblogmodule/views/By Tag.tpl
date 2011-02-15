{*
 * Copyright (c) 2004-2011 OIC Group, Inc.
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

<div class="weblogmodule by-tag">
	<h2>
		{if $moduletitle != ""}{$moduletitle}{/if}
	</h2>
	<ul>
		{foreach from=$tags item=tag}
			{if $tag->cnt != ""}
				<li><a href="{link action=view_bytag id=$tag->id}" title="{$_TR.link_title} '{$tag->name}'">{$tag->name} ({$tag->cnt})</a></li>
			{else}
				{* <li><i>No Posts Tagged with "{$tag->name}"</i></li> *}
			{/if}
		{/foreach}
	</ul>
</div>
