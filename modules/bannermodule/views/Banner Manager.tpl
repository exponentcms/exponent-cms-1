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
<table cellpadding="2" cellspacing="0" width="100%" border="0">
<tr>
	<td class="header banner_header">{$_TR.banner}</td>
	<td class="header banner_header">{$_TR.affiliate}</td>
	<td class="header banner_header"></td>
</tr>
{foreach from=$banners item=banner}
{assign var=aid value=$banner->affiliate_id}
{assign var=fid value=$banner->file_id}
<tr>
	<td valign="top">
		{$banner->name}<br />
		<img class="mngmnt_icon" src="{$smarty.const.PATH_RELATIVE}{$files[$fid]->directory}/{$files[$fid]->filename}" />
	</td>
	<td valign="top">{$affiliates[$aid]}</td>
	<td valign="top">
		<a class="mngmntlink banner_mngmntlink" href="{link action=ad_edit id=$banner->id}">
			<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" border="0" />
		</a>
		<a class="mngmntlink banner_mngmntlink" href="{link action=ad_delete id=$banner->id}" onclick="return confirm('{$_TR.delete_confirm}');">
			<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" border="0" />
		</a>
	</td>
</tr>
{/foreach}
</table>
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.manage == 1 && $noupload != 1}
<a class="mngmntlink banner_mngmntlink" href="{link action=ad_edit}">{$_TR.new_banner}</a>
{/if}
{/permissions}

{if $noupload == 1}
<div class="error">
{$_TR.uploads_disabled}<br />
{if $uploadError == $smarty.const.SYS_FILES_FOUNDFILE}{$_TR.err_foundfile}
{elseif $uploadError == $smarty.const.SYS_FILES_NOTWRITABLE}{$_TR.err_cantmkdir}
{else}{$_TR.err_unknown}
{/if}
</div>
{/if}
