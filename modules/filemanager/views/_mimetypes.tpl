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
<div class="form_title">{$_TR.form_title}</div>
<div class="form_header"><p>{$_TR.form_header}</p>
<a class="mngmntlink administration_mngmntlink" href="{link module=filemanager action=admin_editmimetype}">{$_TR.create_new}</a>
</div>
<table cellpadding="2" cellspacing="0" style="border:none;" width="100%">
<tr>
	<td class="header administration_header">{$_TR.mime_type}</td>
	<td class="header administration_header">{$_TR.name}</td>
	<td align="center" class="header administration_header">{$_TR.icon}</td>
	<td class="header administration_header"></td>
</tr>
{foreach from=$mimetypes item=mimetype}
<tr class="row {cycle values='odd,even'}_row">
<td><b>{$mimetype->mimetype}</b></td>
<td>{$mimetype->name}</td>
<td align ="center">
	{if $mimetype->icon != ""}
	<img class="mngmnt_icon" src="{$smarty.const.MIMEICON_RELATIVE}{$mimetype->icon}"/>
	{else}
	{$_TR.no_icon}
	{/if}
</td>
<td>
	<a class="mngmntlink administration_mngmntlink" href="{link module=filemanager action=admin_editmimetype id=$mimetype->id}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>
	<a class="mngmntlink administration_mngmntlink" href="{link module=filemanager action=admin_deletemimetype id=$mimetype->id}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" /></a>
</td>
</tr>
{/foreach}
</table>
<br />
<a class="mngmntlink administration_mngmntlink" href="{link module=filemanager action=admin_restoremimetypes}">{$_TR.restore}</a>