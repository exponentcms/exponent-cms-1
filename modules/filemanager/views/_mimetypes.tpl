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
<div class="form_header">{$_TR.form_header}
<br /><br />
<a class="mngmntlink administration_mngmntlink" href="{link module=filemanager action=admin_editmimetype}">{$_TR.create_new}</a>
</div>
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="header administration_header">{$_TR.mime_type}</td>
	<td class="header administration_header">{$_TR.name}</td>
	<td align="center" class="header administration_header">{$_TR.icon}</td>
	<td class="header administration_header"></td>
</tr>
{foreach from=$types item=type}
<tr class="row {cycle values='odd,even'}_row">
<td><b>{$type->mimetype}</b></td>
<td>{$type->name}</td>
<td align ="center">
	{if $type->icon != ""}
	<img class="mngmnt_icon" src="{$smarty.const.MIMEICON_RELATIVE}{$type->icon}"/>
	{else}
	{$_TR.no_icon}
	{/if}
</td>
<td>
	<a class="mngmntlink administration_mngmntlink" href="{link module=filemanager action=admin_editmimetype type=$type->mimetype}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}edit.png" /></a>
	<a class="mngmntlink administration_mngmntlink" href="{link module=filemanager action=admin_deletemimetype type=$type->mimetype}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.png" /></a>
</td>
</tr>
{/foreach}
</table>
<br />
<a class="mngmntlink administration_mngmntlink" href="{link module=filemanager action=admin_restoremimetypes}">{$_TR.restore}</a>