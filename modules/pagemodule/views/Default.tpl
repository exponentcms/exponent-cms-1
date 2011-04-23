{*
 *
 * Copyright (c) 2004-2005 OIC Group, Inc.
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
 *}
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}" title="Assign permissions on this Page Displayer"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}" title="Assign group permissions on this Page Displayer"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
{/if}
{/permissions}
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.configure == 1}
	<a href="{link action=configure}" title="Configure this Page Displayer"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}configure.png" title="{$_TR.alt_configure}" alt="{$_TR.alt_configure}" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}
{if $moduletitle != ""}<div class="moduletitle page_moduletitle">{$moduletitle}</div>{/if}
{if $not_configured == 1}This Page Displayer has not been properly configured.
{else}
<div>
{$output}
</div>
{/if}
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.configure == 1}
<br />
{if $noupload == 1}
<div class="error">
Uploads have been disabled.<br />
{if $uploadError == $smarty.const.SYS_FILES_FOUNDFILE}{$smarty.const.TR_FILEMANAGER_FILEFOUNDINPATH}
{elseif $uploadError == $smarty.const.SYS_FILES_NOTWRITABLE}{$smarty.const.TR_FILEMANAGER_CANTMKDIR}
{else}{$smarty.const.TR_FILEMANAGER_UNKNOWNERROR}
{/if}
</div>
{else}
<a href="{link action=upload}" class="mngmntlink page_mngmntlink" title="Upload page to display" alt="Upload page to display">Upload Page</a>
<br />
{/if}
{/if}
{/permissions}

