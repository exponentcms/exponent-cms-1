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
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}" title="Assign permissions on this Banner Module"><img border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}" title="Assign group permissions on this Banner Module"><img border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" /></a>
{/if}
{if $permissions.configure == 1}
	<a href="{link action=configure _common=1}" title="Configure this Banner Module"><img border="0" src="{$smarty.const.ICON_RELATIVE}configure.png" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}
{foreach from=$banners item=banner}
<a href="{$smarty.const.URL_FULL}modules/bannermodule/banner_click.php?id={$banner->id}">
<img src="{$smarty.const.PATH_RELATIVE}{$banner->file->directory}/{$banner->file->filename}" border="0" />
</a>
<br />
{/foreach}
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.manage && $noupload != 1}
<a class="mngmntlink banner_mngmntlink" href="{link action=ad_edit}">New Banner</a>
{/if}
{/permissions}

{if $noupload == 1}
<div class="error">
Uploads have been disabled.<br />
{if $uploadError == $smarty.const.SYS_FILES_FOUNDFILE}Found a file in the directory path when creating the directory to store the files in.
{elseif $uploadError == $smarty.const.SYS_FILES_NOTWRITABLE}Unable to create directory to store files in.
{else}An unknown error has occurred.  Please contact the Exponent Developers.
{/if}
</div>
{/if}
