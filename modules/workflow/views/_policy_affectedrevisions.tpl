{*
 *
 * Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
 *
 * This file is part of Exponent
 *
 * Exponent is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * Exponent is distributed in the hope that it
 * will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR
 * PURPOSE.  See the GNU General Public License
 * for more details.
 *
 * You should have received a copy of the GNU
 * General Public License along with Exponent; if
 * not, write to:
 *
 * Free Software Foundation, Inc.,
 * 59 Temple Place,
 * Suite 330,
 * Boston, MA 02111-1307  USA
 *
 * $Id$
 *}
The following items are current under the authority of the approval policy you are trying to save.<br />
<br />
<form method="post">
<input type="hidden" name="policy" value='{$newpolicy_serial}' />
<input type="hidden" name="module" value="workflow" />
<input type="hidden" name="action" value="admin_savenormalize" />
<table cellpadding="4" cellspacing="1" width="100%" border="0">
	<tr>
		<td class="header workflow_header">Title</td>
		<td class="header workflow_header"> Version</td>
		<td class="header workflow_header">Module</td>
		<td class="header workflow_header">Source</td>
		<td class="header workflow_header" colspan="1"></td>
		<td class="header workflow_header" align="center">Re-evaluate</td>
		<td class="header workflow_header" align="center">Restart</td>
	</tr>
{foreach from=$affected key=type item=posts}
{foreach from=$posts item=post}
	<tr>
		<td>{$post->title}</td>
		<td>{$post->current_major}.{$post->current_minor}</td>
		<td>{$post->module}</td>
		<td>{$post->source}</td>
		<td>
			{if $post->approvals >= $newpolicy->required_approvals}
				<span style="color: green; font-weight: bold;">Approved</span>
			{else}
				No Change
			{/if}
		</td>
		<td align="center">
			<input type="radio" name="selection[{$type}][{$post->real_id}]" value="eval" {if $post->approvals < $newpolicy->required_approvals}checked{/if}/>
		</td>
		<td align="center">
			<input type="radio" name="selection[{$type}][{$post->real_id}]" value="restart" {if $post->approvals >= $newpolicy->required_approvals}checked{/if} />
		</td>
	</tr>
{/foreach}
{/foreach}
	<tr>
		<td colspan="6" align="center">
			<input type="submit" value="Save Policy" />
		</td>
	</tr>
</table>
</form>