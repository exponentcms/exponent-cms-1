{*
 *
 * Copyright 2005 James Hunt and OIC Group, Inc.
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
 
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}" title="Assign permissions on this Module"><img border="0" src="{$smarty.const.ICON_RELATIVE}userperms.gif" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}" title="Assign group permissions on this Module"><img border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.gif" /></a>
{/if}
{if $permissions.configure == 1}
	<a href="{link action=configure _common=1}" title="Configure this Module"><img border="0" src="{$smarty.const.ICON_RELATIVE}configure.gif" /></a>
{/if}
{/permissions}{permissions level=$smarty.const.UILEVEL_NORMAL}
 <table cellspacing="0" cellpadding="0" border="0" width="500">
	<tr>
		<td>{if $permissions.viewdata == 1 && $form->is_saved == 1}<a href="{link action=view_data module=formbuilder}&id={$form->id}">View Data</a>{/if}</td>
		<td>{if $permissions.editformsettings == 1}<a href="{link action=edit_form module=formbuilder}&id={$form->id}">Edit Form Settings</a>{/if}</td>
		<td>{if $permissions.editform == 1}<a href="{link action=view_form module=formbuilder}&id={$form->id}">Edit Form</a>{/if}</td>
		<td>{if $permissions.editreport == 1}<a href="{link action=edit_report module=formbuilder}&id={$form->id}">Edit Report Settings</a>{/if}</td>
	</tr>
</table>
{/permissions}
<br><br>
{$formmsg}
 <div style="border: padding: 1em;">
{$form_html}
</div>
