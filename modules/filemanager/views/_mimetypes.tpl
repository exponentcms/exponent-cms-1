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
<div class="form_title">Manage File Types</div>
<div class="form_header">Whenever a file is uploaded to the site, information about the file format (its MIME type) is stored.  Here, you can define what MIME types are recognized by the site, and optionally associate an icon with each type.
<br /><br />
To add support for a MIME type, use the <a class="mngmntlink administration_mngmntlink" href="{link module=filemanager action=admin_editmimetype}">New MIME Type</a> form.
</div>
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="header administration_header">MIME Type</td>
	<td class="header administration_header">Name</td>
	<td align="center" class="header administration_header">Icon</td>
	<td class="header administration_header"></td>
</tr>
{foreach from=$types item=type}
<tr class="row {cycle values='odd,even'}_row">
<td><b>{$type->mimetype}</b></td>
<td>{$type->name}</td>
<td align ="center">
	{if $type->icon != ""}
	<img src="{$smarty.const.MIMEICON_RELATIVE}{$type->icon}"/>
	{else}
	(no icon)
	{/if}
</td>
<td>
	<a class="mngmntlink administration_mngmntlink" href="{link module=filemanager action=admin_editmimetype type=$type->mimetype}"><img border="0" src="{$smarty.const.ICON_RELATIVE}edit.gif" /></a>
	<a class="mngmntlink administration_mngmntlink" href="{link module=filemanager action=admin_deletemimetype type=$type->mimetype}"><img border="0" src="{$smarty.const.ICON_RELATIVE}delete.gif" /></a>
</td>
</tr>
{/foreach}
</table>
<br />
<a class="mngmntlink administration_mngmntlink" href="{link module=filemanager action=admin_restoremimetypes}">Restore Defaults</a>