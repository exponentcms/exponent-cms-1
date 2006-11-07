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

<h1>Site Index</h1>
<table cellpadding="1" cellspacing="0" border="0" width="100%">
{foreach from=$sections item=section}
<!--tr><td style="padding-left: {math equation="x*20" x=$section->depth}px"-->
<tr><td style="{if $section->active == 1 && $section->numParents == 0}padding-top:25px;{/if}">
{if $section->active == 1}
	{if $section->numParents == 0}
		<a href="{$section->link}" class="navlink"{if $section->new_window} target="_blank"{/if}>{$section->name}</a>&nbsp;
	{else}
		<a href="{$section->link}" class="navlink navlink_small"{if $section->new_window} target="_blank"{/if}>{$section->name}</a>&nbsp;
	{/if}
{else}
<span class="navlink">{$section->name}</span>&nbsp;
{/if}
</td></tr>
{/foreach}
</table>
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $canManage == 1}
<br /><br />
[ <a class="navlink" href="{link action=manage}">{$_TR.manage}</a> ]
{/if}
{/permissions}
