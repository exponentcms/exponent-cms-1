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

<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td class="tab_btn tab_btn_current">
	<a href="{link action=manage}">Hierarchy</a>
</td>
{if $isAdministrator == 1}
<td class="tab_btn tab_btn">
	<a href="{link action=manage_standalone}">Standalone&nbsp;Pages</a>
</td>
<td class="tab_btn">
	<a href="{link action=manage_pagesets}">Pagesets</a>
</td>
{else}
<td></td>
<td></td>
{/if}
<td class="tab_spacer" width="50%">
&nbsp;
</td></tr>
<tr><td colspan="4" class="tab_main">

<div class="moduletitle navigation_moduletitle">Manage Site Navigation</div>
<div class="form_header">
Manage the pages and site structure here.
<br />
<a class="mngmntlink navigation_mngmntlink" href="{link action=add_section parent=0}">New Top Level Page</a>
</div>
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="header navigation_header">Name</td>
	<td class="header navigation_header"><!-- Add Links --></td>
	<td class="header navigation_header"><!-- Edit/Delete Links --></td>
	<td class="header navigation_header"><!-- Permission Links --></td>
	<td class="header navigation_header"><!-- Ranking Links --></td>
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
{if $section->alias_type == 0 && $section->canManage == 1}
<a class="mngmntlink navigation_mngmntlink" href="{link action=add_section parent=$section->id}">Add Subpage</a>
{/if}
</td><td>
{if $section->canManage == 1}
{if $section->alias_type == 0}
<a class="mngmntlink navigation_mngmntlink" href="{link action=edit_contentpage id=$section->id}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" border="0"></a>
<a class="mngmntlink navigation_mngmntlink" href="{link action=remove id=$section->id}" onClick="return confirm('Are you sure you want to move this page and all of its subpages out of the site hiearchy?\r\n(They will not be deleted, but will instead become standalone pages)');"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" border="0"></a>
{elseif $section->alias_type == 1}
{* External Link *}
<a class="mngmntlink navigation_mngmntlink" href="{link action=edit_externalalias id=$section->id}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" border="0"></a>
<a class="mngmntlink navigation_mngmntlink" href="{link action=delete id=$section->id}" onClick="return confirm('Are you sure you want to delete this external alias?');"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" border="0"></a>
{else}
{* Internal Alias *}
<a class="mngmntlink navigation_mngmntlink" href="{link action=edit_internalalias id=$section->id}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" border="0"></a>
<a class="mngmntlink navigation_mngmntlink" href="{link action=delete id=$section->id}" onClick="return confirm('Are you sure you want to delete this internal alias?');"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" border="0"></a>
{/if}
{/if}
</td><td>
{if $section->canAdmin == 1}
	<a href="{link int=$section->id action=userperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="Assign user permissions for viewing this page" alt="Assign user permissions for this page" /></a>
	<a href="{link int=$section->id action=groupperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="Assign group permissions for viewing this page" alt="Assign group permissions for this page" /></a>
{/if}
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