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
 {assign var=bar value=0}
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tnav">
	<tr>
		<td width="100%" align="left" valign="middle" class="tnav">
		&nbsp;&nbsp;|&nbsp;
		{foreach from=$sections item=section}
		{if $section->parent == 0}
		{if $section->name == "Break" && $section->active == 0}
		</td>
	</tr>
	<tr>
		<td height="3">
		</td>
	</tr>
	<tr>
		<td width="100%" align="left" valign="middle" class="tnav">
		&nbsp;&nbsp;|&nbsp;
		{else}		
			{if $section->active == 1}

				<a class="navlink" href="{$section->link}"{if $section->new_window} target="_blank"{/if}>{$section->name}</a>
			{else}
				<span class="navlink">{$section->name}</span>
			{/if}
			&nbsp;|&nbsp;
			{/if}
		{/if}
		{/foreach}
		{permissions level=$smarty.const.UILEVEL_NORMAL}
		{if $canManage == 1}
			&nbsp;[&nbsp;<a class="navlink" href="{link action=manage}">{$_TR.manage}</a>&nbsp;]&nbsp;
		{/if}
		{/permissions}
		</td>
	</tr>
</table>
