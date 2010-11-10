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

<div class="youtubemodule default">
	{if $moduletitle != ""}<h2>{$moduletitle}</h2>{/if}
	{permissions level=$smarty.const.UILEVEL_NORMAL}
	{if $permissions.edit == 1}
		<a href="{link action=edit id=$youtube->id}"><img src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>
	{/if}
	{/permissions}

	{if $youtube->id != 0}
        	<object width="{$youtube->width}" height="{$youtube->height}">
                	<param name="movie" value="{$youtube->url}&hl={$smarty.const.DISPLAY_LANGUAGE}&fs=1" />
                        <param name="wmode" value="transparent" />
                        <embed src="{$youtube->url}"
                               type="application/x-shockwave-flash"
                               wmode="transparent" width="{$youtube->width}" height="{$youtube->height}" />
               </object>
		<p>{$youtube->description}</a>
       {else}
       		<p>No video found.</p>
       {/if}
</div>
