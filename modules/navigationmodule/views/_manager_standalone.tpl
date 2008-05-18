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

<div class="navigationmodule manager-standalone">
	<div class="form_header">
		<h1>{$_TR.form_title}</h1>
		<p>{$_TR.form_header}</p>
		<a class="newpage" href="{link action=edit_contentpage parent=-1}">{$_TR.new}</a>
	</div>

	<table cellpadding="2" cellspacing="0" border="0" width="100%">
	<tr>
		<th><strong>{$_TR.page_title}</strong></th>
		<th><strong>{$_TR.actions}</strong></th>
		<th><strong>{$_TR.permissions}</strong></th>
	</tr>
	{foreach from=$sections item=section}
	{math equation="x+1" x=$section->rank assign=nextrank}
	{math equation="x-1" x=$section->rank assign=prevrank}
	<tr class="{cycle values=odd,even}">
	<td>
		{if $section->active}
			<a href="{link section=$section->id}" class="navlink">{$section->name}</a>&nbsp;
		{else}
			{$section->name}&nbsp;
		{/if}
	</td><td>
		<a class="mngmntlink navigation_mngmntlink" href="{link action=edit_contentpage id=$section->id}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.gif" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>
		<a class="mngmntlink navigation_mngmntlink" href="{link action=delete id=$section->id}" onclick="return confirm('{$_TR.delete_confirm}');"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.gif" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" /></a>
	</td><td>
		<a href="{link int=$section->id action=userperms _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}userperms.gif" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>
		<a href="{link int=$section->id action=groupperms _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}groupperms.gif" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
	</td></tr>
	{foreachelse}
		<tr><td><i>{$_TR.no_pages}</i></td></tr>
	{/foreach}
	</table>
</div>
