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
	<a href="{link action=userperms _common=1}" title="{$_TR.alt_userperm}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}" title="{$_TR.alt_groupperm}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" /></a>
{/if}
{if $permissions.configure == 1}
	<a href="{link action=configure _common=1}" title="{$_TR.alt_configure}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}configure.png" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}
{foreach from=$banners item=banner}
<a href="{$smarty.const.URL_FULL}modules/bannermodule/banner_click.php?id={$banner->id}">
<img class="mngmnt_icon" src="{$smarty.const.PATH_RELATIVE}{$banner->file->directory}/{$banner->file->filename}" border="0" />
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
