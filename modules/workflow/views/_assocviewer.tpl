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
<br /><br />
<hr size="1" />
<div class="form_title">Manage Associations</div>
<div class="form_header">Use the options below to manage the default module-to-policy associations.<br /><br />
To override defaults, or apply a policy to just one module, click <a class="mngmntlink administration_mngmntlink" href="#" onClick="openSelector('all','{$smarty.const.PATH_RELATIVE}modules/workflow/assoc_edit.php?dummy','workflow','_sourcePicker'); return false">here</a></div>
{if $policy_count == 0}
<div style="font-style: italic;">Note: No policies have been defined.  You will have to define one or more approval policies before you can associate them with modules.</div>
<hr size="1"/>
{/if}
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="header workflow_header">Module Type</td>
	<td class="header workflow_header">Default Policy</td>
	<td class="header workflow_header"></td>
</tr>
{foreach name=s from=$modules item=sources key=module}
	{assign var=def value=$defaults[$module]}
<tr class="row {cycle values='odd,even'}_row">
	<td>{$names[$module]}</td>
	<td>{if $def != 0}{$policies[$def]->name}{else}<i>No Policy</i>{/if}</td>
	<td>
		{if $policy_count != 0}
		<a class="mngmntlink workflow_mngmntlink" href="{link action=assoc_edit m=$module p=$def}">
			<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}edit.png" />
		</a>
		{else}
			<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}edit.disabled.png" />
			&nbsp;
		{/if}
	</td>
</tr>
{/foreach}
</table>