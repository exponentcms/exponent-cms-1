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
<div class="newsmodule view-unpublished">
<table>
<caption>{$_TR.expired_news}</caption>
	<tr>
		<th scope="col" class="header news_header" abbr="{$_TR.title}" title="{$_TR.title}">{$_TR.title}</th>
		<th scope="col" class="header news_header" abbr="{$_TR.expired_on}" title="{$_TR.expired_on}">{$_TR.expired_on}</th>
		<th scope="col" class="header news_header" abbr="{$_TR.expired_for}" title="{$_TR.expired_for}">{$_TR.expired_for}</th>
		<th scope="col" class="header news_header">{$_TR.actions}</th>
	</tr>
	{foreach from=$expired item=n}
		<tr>
			<td>
				<a class="mngmntlink news_mngmntlink" href="{link action=view id=$n->id}">{$n->title}</a>
			</td>
			<td>{$n->unpublish|format_date:$smarty.const.DISPLAY_DATE_FORMAT}</td>
			<td>{time_duration assign=td duration=$n->difference type="dhm"}{$td.d} {$_TR.day}{if $td.d != 1}s{/if}, {$td.h} {$_TR.hour}{if $td.h != 1}s{/if} {$_TR.and} {$td.m} {$_TR.minute}{if $td.m != 1}s{/if}</td>
			<td>
				{if $permissions.edit_item == 1 || $n->permissions.edit_item == 1}
					{if $n->approved == 2} {* in ap *}
						<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.disabled.png" title="{$_TR.alt_edit_disabled}" alt="{$_TR.alt_edit_disabled}" />
					{else}
						<a class="mngmntlink news_mngmntlink" href="{link action=edit id=$n->id}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>
					{/if}
				{/if}
				{if $permissions.delete_item == 1 || $n->permissions.delete_item == 1}
					{if $n->approved == 2} {* in ap *}
						<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.disabled.png" title="{$_TR.alt_delete_disabled}" alt="{$_TR.alt_delete_disabled}" />
					{else}
						<a onclick="return confirm('{$_TR.delete_confirm}');" class="mngmntlink news_mngmntlink" href="{link action=delete id=$n->id}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" /></a>
					{/if}
				{/if}
			</td>
		</tr>
	{foreachelse}
		<tr>
			<td colspan="4" align="center"><em>{$_TR.no_expired}</em></td>
		</tr>
	{/foreach}
</table>
<hr />
<table>
<caption>{$_TR.unpublished_news}</caption>
	<tr>
		<th scope="col" class="header news_header">{$_TR.title}</th>
		<th scope="col" class="header news_header">{$_TR.pub_date}</th>
		<th scope="col" class="header news_header">{$_TR.time_to_pub}</th>
		<th scope="col" class="header news_header">{$_TR.actions}</th>
	</tr>
	{foreach from=$unpublished item=n}
		<tr>
			<td>
				<a class="mngmntlink news_mngmntlink" href="{link action=view id=$n->id}">{$n->title}</a>
			</td>
			<td>{$n->publish|format_date:$smarty.const.DISPLAY_DATE_FORMAT}</td>
			<td>{time_duration assign=td duration=$n->difference type="dhm"}{$td.d} {$_TR.day}{if $td.d != 1}s{/if}, {$td.h} {$_TR.hour}{if $td.h != 1}s{/if} {$_TR.and} {$td.m} {$_TR.minute}{if $td.m != 1}s{/if} {$_TR.from_now}</td>
			<td>
				{if $permissions.edit_item == 1 || $n->permissions.edit_item == 1}
					{if $n->approved == 2} {* in ap *}
						<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.disabled.png" title="{$_TR.alt_edit_disabled}" alt="{$_TR.alt_edit_disabled}" />
					{else}
						<a class="mngmntlink news_mngmntlink" href="{link action=edit id=$n->id}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>
					{/if}
				{/if}
				{if $permissions.delete_item == 1 || $n->permissions.delete_item == 1}
					{if $n->approved == 2} {* in ap *}
						<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.disabled.png" title="{$_TR.alt_delete_disabled}" alt="{$_TR.alt_delete_disabled}" />
					{else}
						<a onclick="return confirm('{$_TR.delete_confirm}');" class="mngmntlink news_mngmntlink" href="{link action=delete id=$n->id}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" /></a>
					{/if}
				{/if}
			</td>
		</tr>
	{foreachelse}
		<tr>
			<td colspan="4" align="center"><em>{$_TR.no_unpublished}</em></td>
		</tr>
	{/foreach}
</table>
</div>
