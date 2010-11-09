{*
 * Copyright (c) 2004-2006 OIC Group, Inc.
 * Written and Designed by James Hunt
 *
 * Copyright (c) 2007 ACYSOS S.L. Modified by Ignacio Ibeas
 * Added subcategory function
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
<div class="form_title">
	{$_TR.form_title}
</div>
<div class="form_header">
	<p>{$_TR.form_header}</p>
</div>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td class="header">{$_TR.name}</td>
		<td class="header"></td>
		<td class="header" align="center">{$_TR.color}</td>
		<td class="header" align="center">{$_TR.icon}</td>
		<td class="header"></td>
		<td class="header"></td>
	</tr>
	{foreach name=a from=$categories item=category}
		{math equation="x+1" x=$category->rank assign=nextrank}
		{math equation="x-1" x=$category->rank assign=prevrank}
		<tr class="row {cycle values=odd_row,even_row}">
			<td style="padding-left: {math equation="x*20" x=$category->depth}px">
				<b>{$category->name}</b>		
			</td>
			<td>
				<a class="mngmntlink navigation_mngmntlink" href="{link module=categories action=edit parent=$category->id orig_module=$origmodule src=$loc->src}">{$_TR.new_sub_category}</a>
			</td>
			<td width="30" {if $category->color != null}style="background-color:{$category->color};"{/if}></td>
			<td align="center">
				{if $category->file_id != 0}
					<img border="0" src="{$smarty.const.URL_FULL}thumb.php?file={$category->file->directory}/{$category->file->filename}&height=30&width=30&constraint=1" alt="{$category->name}" title="{$category->name}" />
				{/if}
			</td>
			<td align="right">
				<a href="{link module=categories action=edit id=$category->id orig_module=$origmodule src=$loc->src}">
					<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" border="0" />
				</a>
				<a href="{link module=categories action=delete id=$category->id orig_module=$origmodule src=$loc->src}">
					<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" border="0" />
				</a>
			</td>
			<td>
				{if $category->last == 0}
					<a href="{link module=categories action=rank_switch parent=$category->parent a=$category->rank b=$nextrank orig_module=$origmodule src=$loc->src}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}down.png" border="0" /></a>
				{else}
					<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}down.disabled.png" border="0" />
				{/if}
				{if $category->first == 0}
					<a href="{link module=categories action=rank_switch parent=$category->parent a=$category->rank b=$prevrank orig_module=$origmodule src=$loc->src}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}up.png" border="0" /></a>
				{else}
					<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}up.disabled.png" border="0" />
				{/if}	
			</td>
		</tr>
	{foreachelse}
		<tr>
			<td colspan="2" align="center"><i>{$_TR.no_categories}</i></td>
		</tr>
	{/foreach}
</table>
{br}
<a href="{link module=categories action=edit orig_module=$origmodule parent=0 src=$loc->src}" class="mngmntlink additem">{$_TR.new_category}</a>
{br}
<a href="{link action=return}">{$_TR.return}</a>
