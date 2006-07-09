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
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
	<br />
{/if}
{/permissions}
{if $moduletitle != ""}<div class="moduletitle htmltemplate_moduletitle">{$moduletitle}</div>{/if}
{if $noupload == 1}
<div class="error">
{$_TR.uploads_disabled}<br />
{if $uploadError == $smarty.const.SYS_FILES_FOUNDFILE}{$_TR.file_in_path}
{elseif $uploadError == $smarty.const.SYS_FILES_NOTWRITABLE}{$_TR.file_cant_mkdir}
{else}{$_TR.file_unknown}
{/if}
</div>
<br />
{else}
{$_TR.uploads_enabled}<br />
{/if}
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td class="header htmltemplate_header">{$_TR.template}</td>
	<td class="header htmltemplate_header">&nbsp;</td>
</tr>
{foreach from=$templates item=t}
<tr>
	<td>
		<a class="mngmntlink htmltemplate_mngmntlink" href="{link action=view id=$t->id}">
			{$t->title}
		</a>
	</td>
	<td>
		{permissions level=$smarty.const.UILEVEL_NORMAL}
		{if $permissions.edit == 1}
		<a class="mngmntlink htmltemplate_mngmntlink" href="{link action=edit id=$t->id}">
			<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" border="0" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
		</a>
		{/if}
		{if $permissions.delete == 1}
		<a class="mngmntlink htmltemplate_mngmntlink" href="{link action=delete id=$t->id}" onclick="return confirm('{$_TR.delete_confirm}');">
			<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" border="0" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
		</a>
		{/if}
		{/permissions}
	</td>
</tr>
{foreachelse}
<tr>
	<td align="center"><i>{$_TR.no_templates}</i></td>
</tr>
{/foreach}
</table>
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.create == 1}
<hr size="1" />
<a class="mngmntlink htmltemplate_mngmntlink" href="{link action=edit}">{$_TR.new_template}</a>
&nbsp;&nbsp;
<a class="mngmntlink htmltemplate_mngmntlink" href="{link action=upload}">{$_TR.upload_template}</a>
<br />
{/if}
{/permissions}
