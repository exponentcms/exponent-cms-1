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
<br /><br />
<hr size="1" />
<div class="form_title">
	{$_TR.form_title}
</div>
<div class="form_header">
	<p>{$_TR.form_header}</p>
	<a href="#" onclick="window.open('{$smarty.const.PATH_RELATIVE}source_selector.php?&dest='+escape('{$smarty.const.PATH_RELATIVE}modules/workflow/assoc_edit.php?dummy')+'&vmod=workflow&vview=_sourcePicker','picker','title=no,toolbar=no,width=640,height=480,scrollbars=yes'); return false">{$_TR.single_link}</a>
</div>
{if $policy_count == 0}
	<div><em>{$_TR.no_policies}</em></div>
	<hr size="1"/>
{/if}
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="header workflow_header">{$_TR.module_type}</td>
	<td class="header workflow_header">{$_TR.default_policy}</td>
	<td class="header workflow_header"></td>
</tr>
{foreach name=s from=$modules item=sources key=module}
	{assign var=def value=$defaults[$module]}
<tr class="row {cycle values='odd,even'}_row">
	<td>{$names[$module]}</td>
	<td>{if $def != 0}{$policies[$def]->name}{else}<em>{$_TR.no_policy}</em>{/if}</td>
	<td>
		{if $policy_count != 0}
			{icon action=assoc_edit m=$module p=$def img=edit.png}
		{else}
			{img src="`$smarty.const.ICON_RELATIVE`edit.disabled.png" title=$_TR.alt_edit_disabled alt=$_TR.alt_edit_disabled}
		{/if}
	</td>
</tr>
{/foreach}
</table>
