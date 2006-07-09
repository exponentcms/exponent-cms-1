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
<tr><td class="tab_btn tab_btn_current">
<a href="{link action=manage}">{$_TR.hierarchy}</a>
</td><td class="tab_btn tab_btn">
<a href="{link action=manage_standalone}">{$_TR.standalone_pages}</a>
</td><td class="tab_btn">
<a href="{link action=manage_pagesets}">{$_TR.pagesets}</a>
</td><td class="tab_spacer" width="50%">
&nbsp;
</td></tr>
<tr><td colspan="4" class="tab_main">

<div class="moduletitle navigation_moduletitle">{$_TR.form_title}</div>
<div class="form_header">
{$_TR.form_header}
<br />
<a class="mngmntlink navigation_mngmntlink" href="{link action=add_section parent=0}">{$_TR.new_top_level}</a>
</div>
<table cellpadding="2" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header">&nbsp;</td>
		<td class="header"></td>
		<td class="header"></td>
		<td class="header"></td>
		<td class="header"></td>
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
{if $section->alias_type == 0}
<a class="mngmntlink navigation_mngmntlink" href="{link action=add_section parent=$section->id}">{$_TR.new_sub_page}</a>
{/if}
</td><td>
{if $section->canManage == 1}
{if $section->alias_type == 0}
<a class="mngmntlink navigation_mngmntlink" href="{link action=edit_contentpage id=$section->id}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" border="0"></a>
<a class="mngmntlink navigation_mngmntlink" href="{link action=remove id=$section->id}" onclick="return confirm('{$_TR.delete_page_confirm}');"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" border="0"></a>  
{elseif $section->alias_type == 1}
{* External Link *}
<a class="mngmntlink navigation_mngmntlink" href="{link action=edit_externalalias id=$section->id}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" border="0"></a>
<a class="mngmntlink navigation_mngmntlink" href="{link action=delete id=$section->id}" onclick="return confirm('{$_TR.delete_ext_confirm}');"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" border="0"></a>
{else}
{* Internal Alias *}
<a class="mngmntlink navigation_mngmntlink" href="{link action=edit_internalalias id=$section->id}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" border="0"></a>
<a class="mngmntlink navigation_mngmntlink" href="{link action=delete id=$section->id}" onclick="return confirm('{$_TR.delete_int_confirm}');"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" border="0"></a>
{/if}
{/if}
</td><td>
	<a href="{link int=$section->id action=userperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>
	<a href="{link int=$section->id action=groupperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_userperm}" /></a>
</td><td>
{if $section->canManageRank == 1}
{if $section->last == 0}
	<a href="{link action=order parent=$section->parent a=$section->rank b=$nextrank}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}down.png" border="0" /></a>
{else}
	<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}down.disabled.png" border="0" />
{/if}
{if $section->first == 0}
	<a href="{link action=order parent=$section->parent a=$section->rank b=$prevrank}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}up.png" border="0" /></a>
{else}
	<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}up.disabled.png" border="0" />
{/if}
{/if}
</td></tr>
{/foreach}
</table>

</td></tr>
</table>