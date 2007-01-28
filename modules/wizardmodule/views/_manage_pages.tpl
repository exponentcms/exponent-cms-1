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
 * $Id: _view.tpl,v 1.7 2005/04/08 15:45:49 filetreefrog Exp $
 *}
<div class="moduletitle wizard_moduletitle">Wizard Manager - Manage Pages</div>
<div>From here you can create new pages, or edit existing pages.</div>

<br />
<table cellpadding="0" cellspacing="1" style="border:none;" width="100%">
<tr>
	<td class="header wizard_header">Name</td>
	<td class="header wizard_header">Description</td>
	<td class="header wizard_header">&nbsp;</td>
</tr>
{foreach name=a from=$pages item=page}
{math equation="x-1" x=$page->rank assign=prev}
{math equation="x+1" x=$page->rank assign=next}
<tr class="row {cycle values=even_row,odd_row}">
	<td>
		{$page->name}
	</td>
	<td>{$page->description}</td>
	<td width="105">
		<a href="{link module="wizardmodule" action="edit_page" id=$page->id}" class="mngmntlink wizard_mngmntlink" title="Edit the name or description for this page">
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
		</a>
		<a href="{link module="wizardmodule" action="delete_page" id=$page->id}" class="mngmntlink wizard_mngmntlink" title="Delete this page">
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
		</a>
		<a href={link module="wizardmodule" action="view_form" wizard_page_id=$page->id} title="Edit the form on this page">
			<img style="border:none;" src="{$smarty.const.ICON_RELATIVE}manage_pages.gif" title="{$_TR.alt_manage_pages}" alt="{$_TR.alt_manage_pages}" />
		</a>
		{if $smarty.foreach.a.first == 0}
                	<a href="{link module=wizardmodule action=rank_switch a=$page->rank b=$prev id=$page->id wizard_id=$page->wizard_id}">
                		<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}up.png" title="{$_TR.alt_up}" alt="{$_TR.alt_up}" />
                	</a>
		{else}
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}up.disabled.png" title="{$_TR.alt_up_disabled}" alt="{$_TR.alt_up_disabled}" />
                {/if}
		{if $smarty.foreach.a.last == 0}
                	<a href="{link module=wizardmodule action=rank_switch a=$next b=$page->rank id=$page->id wizard_id=$page->wizard_id}">
                	<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}down.png" title="{$_TR.alt_down}" alt="{$_TR.alt_down}" />
                	</a>
		{else}
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}down.disabled.png" title="{$_TR.alt_down_disabled}" alt="{$_TR.alt_down_disabled}" />
                {/if}
	</td>
</tr>
{foreachelse}
<tr>
	<td colspan="3"><i>No pages have been created for this wizard yet. </i></td>
</tr>
{/foreach}
</table>

<a class="navlink" href="{link module="wizardmodule" action="new_page" wizard_id=$wizard_id}">Create New Page</a>
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.administrate == 1}
<br />
<a class="navlink" href="{link module="wizardmodule" action="new_wizard"}">New Wizard</a>
{/if}
{/permissions}
