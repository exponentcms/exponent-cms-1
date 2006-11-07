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
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1 int=$int}" title="Assign permissions on this Forum"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" /></a>&nbsp;
	<a href="{link action=groupperms _common=1 int=$int}" title="Assign group permissions on this Forum"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" /></a>
{/if}
{/permissions}

<div class="moduletitle wizard_moduletitle">Wizard Manager</div>
<div>From here you can create new wizards, or edit existing wizards.</div>

<br />
<table cellpadding="0" cellspacing="1" border="0" width="100%">
<tr>
	<td class="header wizard_header">Name</td>
	<td class="header wizard_header" colspan="2">Description</td>
</tr>
{foreach from=$wizards item=wizard}
<tr class="row {cycle values=even_row,odd_row}">
	<td>
		<a href="{link module="wizardmodule" action="edit_wizard" id=$wizard->id}" class="mngmntlink wizard_mngmntlink">{$wizard->name}</a>
	</td>
	<td>{$wizard->description}</td>
	<td>
		<a href="{link module="wizardmodule" action="delete_wizard" id=$wizard->id}" title="Delete this wizard">
			<img border="0" src="{$smarty.const.ICON_RELATIVE}delete.gif" />
		</a>
		<a href="{link module="wizardmodule" action="manage_pages" wizard_id=$wizard->id}" title="Manages pages for this wizard">
			<img border="0" src="{$smarty.const.ICON_RELATIVE}manage_pages.gif" />
		</a>
	</td>
</tr>
{foreachelse}
<tr>
	<td colspan="3"><i>No wizards have been setup yet. </i></td>
</tr>
{/foreach}
</table>

<a class="navlink" href="{link module="wizardmodule" action="new_wizard"}">Create New Wizard</a>
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.administrate == 1}
<br />
<a class="navlink" href="{link module="wizardmodule" action="new_wizard"}">New Wizard</a>
{/if}
{/permissions}
