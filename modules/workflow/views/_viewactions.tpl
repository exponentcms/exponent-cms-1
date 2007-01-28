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
<div class="form_title">{$_TR.form_title}</div>
<div class="form_header">{$_TR.form_header}</div>
<table cellpadding="0" cellspacing="4" width="100%" border="0">
{foreach from=$names item=name key=type}
{capture assign=linkend}&policy_id={$policy_id}&type={$type}{/capture}
<tr><td><br /><br /></td></tr>
<tr>
	<td colspan="4" style="border-top: 1px dashed lightgrey;">
		<h3>{$name}</h3>
	</td>
</tr>
{foreach name=a from=$actions[$type] item=action}
{math equation="x-2" x=$smarty.foreach.a.iteration assign=prev}
{math equation="x-1" x=$smarty.foreach.a.iteration assign=this}
{assign var=next value=$smarty.foreach.a.iteration}
{* Assign linkend *}
<tr>
	<td>
		{$action->method}
	</td>
	<td align="right">
		<a class="mngmntlink workflow_mngmntlink" href="{link action=action_edit id=$action->id}">
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
		</a>
		<a class="mngmntlink workflow_mngmntlink" href="{link action=action_delete id=$action->id}">
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
		</a>
	</td>
	<td width="16">
		<a class="mngmntlink workflow_mngmntlink" href="{link action=action_switch a=$this b=$prev policy_id=$policy_id type=$type}">
			{if $smarty.foreach.a.first == 0}
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}up.png" title="{$_TR.alt_up}" alt="{$_TR.alt_up}" />
			{/if}
		</a>
	</td><td width="16">
		<a class="mngmntlink workflow_mngmntlink" href="{link action=action_switch a=$next b=$this policy_id=$policy_id type=$type}">
			{if $smarty.foreach.a.last == 0}
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}down.png" title="{$_TR.alt_down}" alt="{$_TR.alt_down}" />
			{/if}
		</a>
	</td>
</tr>
{foreachelse}
<tr>
	<td colspan="4" align="center">
		<i>{$_TR.no_actions}</i><br />
	</td>
</tr>
{/foreach}
<tr>
	<td colspan="4" align="center">
		<a class="mngmntlink workflow_mngmntlink" href="{link action=action_edit policy_id=$policy_id type=$type}">{$_TR.add_action}</a>
	</td>
</tr>
{/foreach}
</table>