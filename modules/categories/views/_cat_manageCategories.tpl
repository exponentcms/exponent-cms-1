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
<div class="form_header">
        <h1>{$_TR.form_title}</h1>
        <p>{$_TR.form_header}</p>
</div>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td class="header" width="40">&nbsp;</td>
	<td class="header">{$_TR.name}</td>
	<td class="header"></td>
</tr>
{foreach name=a from=$categories item=category}
{math equation="x-2" x=$smarty.foreach.a.iteration assign=prev}
{math equation="x-1" x=$smarty.foreach.a.iteration assign=this}
{assign var=next value=$smarty.foreach.a.iteration}
<tr class="row {cycle values=odd_row,even_row}">
	<td>
		<div style="width: 32px; height: 16px; background-color: {$category->color}">&nbsp;</div>
	</td>
	<td>
		<b>{$category->name}</b>		
	</td>
	<td align="right">
		<a href="{link module=categories action=edit id=$category->id orig_module=$origmodule}">
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
		</a>
		<a href="{link module=categories action=delete id=$category->id orig_module=$origmodule}">
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
		</a>	
		{if $smarty.foreach.a.first == 0}
		<a href="{link action=rank_switch a=$this b=$prev id=$category->id orig_module=$origmodule}">
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}up.png" title="{$_TR.alt_up}" alt="{$_TR.alt_up}" />
		</a>
		{else}
		<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}up.disabled.png" title="{$_TR.alt_up_disabled}" alt="{$_TR.alt_up_disabled}" />
		{/if}
		
		{if $smarty.foreach.a.last == 0}
		<a href="{link action=rank_switch a=$next b=$this id=$category->id orig_module=$origmodule}">
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}down.png" title="{$_TR.alt_down}" alt="{$_TR.alt_down}" />
		</a>
		{else}
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}down.disabled.png" title="{$_TR.alt_down_disabled}" alt="{$_TR.alt_down_disabled}" />
		{/if}
		
	</td>
<tr>
{foreachelse}
<tr>
	<td colspan="2" align="center"><i>{$_TR.no_categories}</i></td>
</tr>
{/foreach}
</table>
<br /><br />
<a href="{link module=categories action=edit orig_module=$origmodule}" class="mngmntlink mngmntlink">{$_TR.new_category}</a>
