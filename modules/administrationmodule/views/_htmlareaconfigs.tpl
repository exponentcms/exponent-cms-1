{*
 *
 * Copyright 2004 James Hunt and OIC Group, Inc.
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
<div class="form_title">HTMLArea Toolbar Settings</div>
<div class="form_header">HTMLArea is a javascript WYSIWYG (What You See is What You Get) HTML editor that is very flexible.  This form allows you to set up toolbar configurations which govern what buttons are available on the toolbar for editors to use.
<br /><br />
The active configuration is used for all HTMLArea controls across the entire site.
<br /><br />
To create a new toolbar, use the <a class="mngmntlink administration_mngmntlink" href="{link action=htmlarea_editconfig id=0}">New Configuration</a> form.
</div>
<table cellpadding="2" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header administration_header">Configuration Name</td>
		<td class="header administration_header">Active?</td>
		<td class="header administration_header"></td>
	</tr>
	{foreach from=$configs item=config}
		<tr>
			<td>{$config->name}</td>
			<td>
				{if $config->active == 1}<b>yes</b>{else}no{/if}
			</td>
			<td>
				<a class="mngmntlink administration_mngmntlink" href="{link action=htmlarea_editconfig id=$config->id}">
					<img src="{$smarty.const.ICON_RELATIVE}edit.gif" border="0" />
				</a>
				{if $config->active == 0}
				<a class="mngmntlink administration_mngmntlink" href="{link action=htmlarea_deleteconfig id=$config->id}">
					<img src="{$smarty.const.ICON_RELATIVE}delete.gif" border="0" />
				</a>
				{else}
				<img src="{$smarty.const.ICON_RELATIVE}delete.disabled.gif" border="0" />
				{/if}
			</td>
		</tr>
	{foreachelse}
		<tr>
			<td colspan="2" align="center">
				<i>No Configurations have been defined.</i>
			</td>
		</tr>
	{/foreach}
</table>