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
{$_TR.warning}<br /><br />
<form method="post">
<input type="hidden" name="policy" value='{$newpolicy_serial}' />
<input type="hidden" name="module" value="workflow" />
<input type="hidden" name="action" value="admin_savenormalize" />
<table cellpadding="4" cellspacing="1" width="100%" border="0">
	<tr>
		<td class="header workflow_header">{$_TR.title}</td>
		<td class="header workflow_header">{$_TR.version}</td>
		<td class="header workflow_header">{$_TR.module}</td>
		<td class="header workflow_header" colspan="1"></td>
		<td class="header workflow_header" align="center">{$_TR.re_evaluate}</td>
		<td class="header workflow_header" align="center">{$_TR.restart}</td>
	</tr>
{foreach from=$affected key=type item=posts}
{foreach from=$posts item=post}
	<tr>
		<td>{$post->title}</td>
		<td>{$post->current_major}.{$post->current_minor}</td>
		<td>{$post->module}</td>
		<td>
			{if $post->approvals >= $newpolicy->required_approvals}
				<span style="color: green; font-weight: bold;">{$_TR.approved}</span>
			{else}
				{$_TR.no_change}
			{/if}
		</td>
		<td align="center">
			<input type="radio" name="selection[{$type}][{$post->real_id}]" value="eval" {if $post->approvals < $newpolicy->required_approvals}checked="checked"{/if}/>
		</td>
		<td align="center">
			<input type="radio" name="selection[{$type}][{$post->real_id}]" value="restart" {if $post->approvals >= $newpolicy->required_approvals}checked="checked"{/if} />
		</td>
	</tr>
{/foreach}
{/foreach}
	<tr>
		<td colspan="6" align="center">
			<input type="submit" value="{$_TR.save}" />
		</td>
	</tr>
</table>
</form>