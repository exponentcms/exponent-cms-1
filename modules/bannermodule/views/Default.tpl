{*
 * Copyright (c) 2004-2011 OIC Group, Inc.
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

{foreach from=$banners item=banner}
	<a href="{$smarty.const.URL_FULL}modules/bannermodule/banner_click.php?id={$banner->id}">
		<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.PATH_RELATIVE}{$banner->file->directory}/{$banner->file->filename}" />
	</a>
	<br />
{/foreach}
{permissions level=$smarty.const.UILEVEL_NORMAL}
	{if $permissions.manage && $noupload != 1}
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