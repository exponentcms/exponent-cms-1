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
<b>{$_TR.expired_news}</b>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header news_header" width="30%">{$_TR.title}</td>
		<td class="header news_header" width="30%">{$_TR.expired_on}</td>
		<td class="header news_header" width="30%">{$_TR.expired_for}</td>
		<td class="header news_header" width="10%"></td>
	</tr>
	{foreach from=$expired item=n}
		<tr>
			<td>
				<a class="mngmntlink news_mngmntlink" href="{link action=view id=$n->id}">{$n->title}</a>
			</td>
			<td>{$n->unpublish|format_date:$smarty.const.DISPLAY_DATE_FORMAT}</td>
			<td>{time_duration assign=td duration=$n->difference type="dhm"}{$td.d} day{if $td.d != 1}s{/if}, {$td.h} hour{if $td.h != 1}s{/if} and {$td.m} minute{if $td.m != 1}s{/if}</td>
			<td>
				{if $permissions.edit_item == 1 || $n->permissions.edit_item == 1}
					{if $n->approved == 2} {* in ap *}
					<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.disabled.png" title="{$_TR.alt_edit_disabled}" alt="{$_TR.alt_edit_disabled}" />
					{else}
					<a class="mngmntlink news_mngmntlink" href="{link action=edit id=$n->id}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>
					{/if}
				{/if}
				{if $permissions.delete_item == 1 || $n->permissions.delete_item == 1}
					{if $n->approved == 2} {* in ap *}
					<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.disabled.png" title="{$_TR.alt_delete_disabled}" alt="{$_TR.alt_delete_disabled}" />
					{else}
					<a onclick="return confirm('{$_TR.delete_confirm}');" class="mngmntlink news_mngmntlink" href="{link action=delete id=$n->id}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" /></a>
					{/if}
				{/if}
			</td>
		</tr>
	{foreachelse}
		<tr>
			<td colspan="3" align="center"><i>{$_TR.no_expired}</i></td>
		</tr>
	{/foreach}
</table>

<hr size="1" />
<b>{$_TR.unpublished_news}</b>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header news_header" width="30%">{$_TR.title}</td>
		<td class="header news_header" width="30%">{$_TR.pub_date}</td>
		<td class="header news_header" width="30%">{$_TR.time_to_pub}</td>
		<td class="header news_header" width="10%"></td>
	</tr>
	{foreach from=$unpublished item=n}
		<tr>
			<td>
				<a class="mngmntlink news_mngmntlink" href="{link action=view id=$n->id}">{$n->title}</a>
			</td>
			<td>{$n->publish|format_date:$smarty.const.DISPLAY_DATE_FORMAT}</td>
			<td>{time_duration assign=td duration=$n->difference type="dhm"}{$td.d} day{if $td.d != 1}s{/if}, {$td.h} hour{if $td.h != 1}s{/if} and {$td.m} minute{if $td.m != 1}s{/if} from now</td>
			<td>
				{if $permissions.edit_item == 1 || $n->permissions.edit_item == 1}
					{if $n->approved == 2} {* in ap *}
					<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.disabled.png" title="{$_TR.alt_edit_disabled}" alt="{$_TR.alt_edit_disabled}" />
					{else}
					<a class="mngmntlink news_mngmntlink" href="{link action=edit id=$n->id}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>
					{/if}
				{/if}
				{if $permissions.delete_item == 1 || $n->permissions.delete_item == 1}
					{if $n->approved == 2} {* in ap *}
					<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.disabled.png" title="{$_TR.alt_delete_disabled}" alt="{$_TR.alt_delete_disabled}" />
					{else}
					<a onclick="return confirm('{$_TR.delete_confirm}');" class="mngmntlink news_mngmntlink" href="{link action=delete id=$n->id}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" /></a>
					{/if}
				{/if}
			</td>
		</tr>
	{foreachelse}
		<tr>
			<td colspan="3" align="center"><i>{$_TR.no_unpublished}</i></td>
		</tr>
	{/foreach}
</table>