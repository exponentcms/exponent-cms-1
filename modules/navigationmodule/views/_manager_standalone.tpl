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
<table cellpadding="0" cellspacing="0">
<tr><td class="tab_btn">
<a href="{link action=manage}">{$_TR.hierarchy}</a>
</td><td class="tab_btn tab_btn_current">
<a href="{link action=manage_standalone}">{$_TR.standalone_pages}</a>
</td><td class="tab_btn">
<a href="{link action=manage_pagesets}">{$_TR.pagesets}</a>
</td><td class="tab_spacer" width="50%">
&nbsp;
</td></tr>
<tr><td colspan="4" class="tab_main">
 
<div class="moduletitle navigation_moduletitle">{$_TR.form_title}</div>
<div class="form_header">{$_TR.form_header}<br />
<a class="mngmntlink navigation_mngmntlink" href="{link action=edit_contentpage parent=-1}">{$_TR.new}</a>
</div>
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="header navigation_header"></td>
	<td class="header navigation_header"><!-- Edit/Delete Links --></td>
	<td class="header navigation_header"><!-- Permission Links --></td>
</tr>
{foreach from=$sections item=section}
{math equation="x+1" x=$section->rank assign=nextrank}
{math equation="x-1" x=$section->rank assign=prevrank}
<tr class="row {cycle values=odd_row,even_row}"><td style="padding-left: {math equation="x*20" x=$section->depth}px">
{if $section->active}
<a href="{link section=$section->id}" class="navlink">{$section->name}</a>&nbsp;
{else}
{$section->name}&nbsp;
{/if}
</td><td>
<a class="mngmntlink navigation_mngmntlink" href="{link action=edit_contentpage id=$section->id}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" border="0"></a>
<a class="mngmntlink navigation_mngmntlink" href="{link action=delete id=$section->id}" onClick="return confirm('{$_TR.delete_confirm}');"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" border="0"></a>
</td><td>
	<a href="{link int=$section->id action=userperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>
	<a href="{link int=$section->id action=groupperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
</td></tr>
{foreachelse}
<tr><td><i>{$_TR.no_pages}</i></td></tr>
{/foreach}
</table>

</td></tr>
</table>