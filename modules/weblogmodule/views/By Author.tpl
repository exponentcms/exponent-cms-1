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

<div class="weblogmodule by-author">
{if $moduletitle != ""}<h1>{$moduletitle}</h1>{/if}

<ul>
{foreach from=$authors item=author}
	<li><a href="{link action=view_byauthor id=$author->id}">{attribution user=$author} ({$author->count})</a></li>
{/foreach}
</ul>
</div>
