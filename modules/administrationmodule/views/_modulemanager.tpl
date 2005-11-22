{*
 *
 * Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
 * All Changes as of 6/1/05 Copyright 2005 James Hunt
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
<div class="form_title">{$_TR.form_title}</div>
<div class="form_header">{$_TR.form_header}<br /><br />
<a class="mngmntlink administration_mngmntlink" href="{link action=upload_extension}">{$_TR.upload_module}</a></div>
<hr size="1" />
<a href="{link action=modmgr_activate all=1 activate=1}">{$_TR.activate_all}</a>
&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="{link action=modmgr_activate all=1 activate=0}">{$_TR.deactivate_all}</a>
<table cellpadding="4" cellspacing="0" border="0" width="100%">
	{foreach from=$modules item=module}
	<tr>
		<td class="administration_modmgrheader"><b>{$module->name}</b> {$_TR.by} {$module->author}</td>
		<td class="administration_modmgrheader" align="right">{if $module->active == 1}<span class="active">{$_TR.active}</span>{else}<span class="inactive">{$_TR.inactive}</span>{/if}</td>
	</tr>
	<tr>
		<td colspan="3" class="administration_modmgrbody">
			{if $module->active == 1}
			<a class="mngmntlink administration_mngmntlink" href="{link action=modmgr_activate mod=$module->class activate=0}">{$_TR.deactivate}</a> - {$_TR.deactivate_reason}
			{else}
			<a class="mngmntlink administration_mngmntlink" href="{link action=modmgr_activate mod=$module->class activate=1}">{$_TR.activate}</a> - {$_TR.activate_reason}
			{/if}
			<br />
			<a class="mngmntlink administration_mngmntlink" href="{link module=info action=showfiles type=$smarty.const.CORE_EXT_MODULE name=$module->class}">{$_TR.view_files}</a>
			&nbsp;&nbsp;|&nbsp;&nbsp;
			<a class="mngmntlink administration_mngmntlink" href="{link action=examplecontent name=$module->class}">{$_TR.example_content}</a>
			<hr size="1"/>
			{$module->description}
		</td>
	</tr>
	<tr><td></td></tr>
	{/foreach}
</table>