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
<b>Expired News</b>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header news_header" width="30%">Title</td>
		<td class="header news_header" width="30%">Expiration date</td>
		<td class="header news_header" width="30%">Expired for</td>
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
					<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}edit.disabled.png" title="Editting Disabled - News Item In Approval" alt="Editting Disabled - News Item In Approval" />
					{else}
					<a class="mngmntlink news_mngmntlink" href="{link action=edit id=$n->id}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}edit.png" title="Edit this News Item" alt="Edit this News Item" /></a>
					{/if}
				{/if}
				{if $permissions.delete_item == 1 || $n->permissions.delete_item == 1}
					{if $n->approved == 2} {* in ap *}
					<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.disabled.png" title="Editting Disabled - News Item In Approval" alt="Deleting Disabled - News Item In Approval" />
					{else}
					<a onClick="return confirm('Are you sure you want to delete this news item?');" class="mngmntlink news_mngmntlink" href="{link action=delete id=$n->id}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.png" title="Delete this News Item" alt="Delete this News Item" /></a>
					{/if}
				{/if}
			</td>
		</tr>
	{foreachelse}
		<tr>
			<td colspan="3" align="center"><i>No Expired News</i></td>
		</tr>
	{/foreach}
</table>

<hr size="1" />
<b>Awaiting Publication</b>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header news_header" width="30%">Title</td>
		<td class="header news_header" width="30%">Publication Date</td>
		<td class="header news_header" width="30%">Time to publication</td>
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
					<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}edit.disabled.png" title="Editting Disabled - News Item In Approval" alt="Editting Disabled - News Item In Approval" />
					{else}
					<a class="mngmntlink news_mngmntlink" href="{link action=edit id=$n->id}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}edit.png" title="Edit this News Item" alt="Edit this News Item" /></a>
					{/if}
				{/if}
				{if $permissions.delete_item == 1 || $n->permissions.delete_item == 1}
					{if $n->approved == 2} {* in ap *}
					<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.disabled.png" title="Editting Disabled - News Item In Approval" alt="Deleting Disabled - News Item In Approval" />
					{else}
					<a onClick="return confirm('Are you sure you want to delete this news item?');" class="mngmntlink news_mngmntlink" href="{link action=delete id=$n->id}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.png" title="Delete this News Item" alt="Delete this News Item" /></a>
					{/if}
				{/if}
			</td>
		</tr>
	{foreachelse}
		<tr>
			<td colspan="3" align="center"><i>No Unpublished News</i></td>
		</tr>
	{/foreach}
</table>