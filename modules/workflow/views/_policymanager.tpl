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

<div class="module workflow policymanager">

<div class="form_header">
        <h1>{$_TR.form_title}</h1>
        <p>{$_TR.form_header}</p>
</div>

<table cellpadding="2" cellspacing="0" width="100%" border="0">
	<tr>
		<td width="20%" class="header administration_header">{$_TR.policy_name}</td>
		<td class="header administration_header">{$_TR.description}</td>
		<td width="40" class="header administration_header"></td>
	</tr>
	{foreach from=$policies item=policy}
	<tr class="row {cycle values='odd,even'}_row">
		<td valign="top">
			{$policy->name}
			<br />
		</td>
		<td valign="top">{$policy->description}</td>
		<td valign="top">
			{icon action=admin_editpolicy id=$policy->id img=edit}
			{icon action=admin_confirmdeletepolicy id=$policy->id img=delete}
			<a class="mngmntlink administration_mngmntlink" href="{link action=admin_viewactions id=$policy->id}">{$_TR.manage_actions}</a>
			<br />
		</td>
	</tr>
	{foreachelse}
		<tr><td colspan="3" align="center" style="font-style: italic">{$_TR.no_policies}</td></tr>
	{/foreach}
	<tr><td colspan="3">
		<a class="mngmntlink administration_mngmntlink" href="{link action=admin_editpolicy}">{$_TR.new_policy}</a>
	</td></tr>
</table>
</div>
