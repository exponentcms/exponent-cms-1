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
<div class="tags manage-collection">
	<div class="form-header">
		<h1>{$_TR.form_title}</h1>
		<p>{$_TR.form_header}</p>
	</div>
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td class="header" style="padding-left: 5px;">{$_TR.name}</td>
		<td class="header">{$_TR.desc}</td>
		<td class="header"></td>
	</tr>
	{foreach name=a from=$tag_collections item=collection}
	{math equation="x-2" x=$smarty.foreach.a.iteration assign=prev}
	{math equation="x-1" x=$smarty.foreach.a.iteration assign=this}
	{assign var=next value=$smarty.foreach.a.iteration}
	<tr class="row {cycle values=odd_row,even_row}">
		<td style="padding-left: 5px;">
			<b>{$collection->name}</b>
		</td>
		<td>
			{$collection->description}		
		</td>
		<td align="right" style="padding-right: 5px;">
			<a href="{link module=tags action=add_tags id=$collection->id}" title="Add tags to this collection" style="border: 0px solid black;">
				<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}tag.png" title="{$_TR.alt_tag}" alt="{$_TR.alt_tag}" />
			</a>
			<a href="{link module=tags action=edit_collection id=$collection->id}" title="Edit this tag collection" style="border: 0px solid black;">
				<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
			</a>
			<a href="{link module=tags action=delete_collection id=$collection->id}" title="Delete this tag collection" style="border: 0px solid black;">
				<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
			</a>	
		</td>
	<tr>
	{foreachelse}	
	<tr>
		<td colspan="2" align="center"><i>{$_TR.no_collections}</i></td>
	</tr>
	{/foreach}
	</table>
	<br /><br />
	<a href="{link module=tags action=edit_collection}" class="mngmntlink mngmntlink">{$_TR.new_collection}</a>
</div>
