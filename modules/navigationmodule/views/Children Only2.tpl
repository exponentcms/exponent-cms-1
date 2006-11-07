{*
 * Copyright (c) 2004-2005 OIC Group, Inc.
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
{foreach from=$sections item=section}

{if $section->numParents != 0 && ($section->parents[0] == $current->id || $section->parents[0] == $current->parents[0])}

{if $section->depth!=2}
{if $section->active == 1}
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="{$section->link}" class="sublink"{if $section->new_window} target="_blank"{/if}>{$section->name}</a>
&nbsp;&nbsp;
{else}
{/if}
{/if}
{/if}
{/foreach}