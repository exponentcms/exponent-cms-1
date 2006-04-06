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
<table cellpadding="4" cellspacing="0" border="0" width="100%">
{foreach from=$summaries item=summary}
{assign var=posterid value=$summary->state_data[0][0]}
	<tr>
		<td style="background-color: lightgrey">
			{$summary->real->title} v{$summary->current_major}.{$summary->current_minor}
			{if $permissions.manage_approval == 1}
			<a class="mngmntlink workflow_mngmntlink" href="{link datatype=$datatype action=delete id=$summary->real_id major=$summary->current_major}" onClick="return confirm('{$_TR.delete_confirm}');"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" border="0"/></a>
			{/if}
		</td>
		<td style="background-color: lightgrey" align="right">
			{if $summary->open_slots <= 0}{$_TR.closed}{else}{$_TR.open}{/if}
		</td>
	</tr>
	<tr>
		<td colspan="2" style="padding-left: 35px;padding-right: 35px; border: 1px solid lightgrey">
			{$_TR.last_updated}: {$summary->updated|format_date:$smarty.const.DISPLAY_DATE_FORMAT}
			<br />
			{$_TR.policy}: <b>{$summary->policy->name}</b>
			<div class="workflow_comment">{$_TR.comment}: <i>{$summary->revision->wf_comment}</i></div>
			<table width="100%" style="border-top: 1px dashed lightgrey; margin-top: 7px;">
				{assign var=involved value=0}
				{foreach from=$summary->involved item=person}
					{assign var=personid value=$person->id}
					<tr>
						<td style="padding-left: 20px; border-right: 1px dashed lightgrey;">{if $posterid == $personid}{$_TR.poster}:{else}{$_TR.approver}:{/if}</td>
						<td style="padding-left: 20px; border-right: 1px dashed lightgrey;">{$summary->involved[$personid]->username}</td>
						<td style="padding-left: 20px;">{if $summary->state_data[1][$personid] == 1}{$_TR.approved}{else}{$_TR.not_approved}{/if}</td>
						<td>
						{if $personid == $user->id}
							{assign var=involved value=1}
							<a class="mngmntlink workflow_mngmntlink" href="{link datatype=$datatype  action=preview_content id=$summary->real_id}">{$_TR.change}</a>
						{/if}
						</td>
					</tr>
				{/foreach}
				{if ($summary->open_slots > 0 and $involved == 0 and $permissions.approve == 1) || ($summary->open_slots <= 0 && $user->is_acting_admin)}
				<tr>
					<td colspan="3" align="center" style="border-top: 1px dashed lightgrey;">
						<a class="mngmntlink workflow_mngmntlink" href="{link datatype=$datatype  action=preview_content id=$summary->real_id}">{$_TR.become}</a>
					</td>
				</tr>
				{/if}
			</table>
		</td>
	</tr>
	
	<tr><td></td></tr>
{foreachelse}
	<tr><td>{$_TR.no_posts}</td></tr>
{/foreach}
</table>