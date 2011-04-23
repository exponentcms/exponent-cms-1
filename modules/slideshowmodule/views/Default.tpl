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
	<a href="{link action=userperms _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>
	<a href="{link action=groupperms _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
{/if}
{if $permissions.configure == 1}
        	<a href="{link action=configure _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}configure.png" title="{$_TR.alt_configure}" alt="{$_TR.alt_configure}" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}
{if $moduletitle != ''}<div class="moduletitle">{$moduletitle}</div>{/if}
{if $number > 0}
<img id="{$unique}_slideshowImg" {if $config->img_width != 0}width="{$config->img_width}" {/if}{if $config->img_height != 0}height="{$config->img_height}" {/if}src="{$smarty.const.URL_FULL}{$slides[0]->file->directory}/{$slides[0]->file->filename}" /><br />
<script type="text/javascript">
{$unique}_images = new Array({foreach name=s from=$slides item=slide}'{$smarty.const.URL_FULL}{$slide->file->directory}/{$slide->file->filename}'{if $smarty.foreach.s.last != 1},{/if}{/foreach});
g_{$unique}_delay = {$config->delay};
g_{$unique}_random = {$config->random};
</script>
<script type="text/javascript" src="{$smarty.const.URL_FULL}modules/slideshowmodule/slideshow.js.php?u={$unique}"></script>
{else}
There are no slides in the slideshow.<br />
{/if}
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.create_slide == 1 || $permissions.edit_slide == 1 || $permissions.delete_slide == 1}
<a class="mngmntlink slideshow_mngmntlink" href="{link action=manage_slides}">Manage Slides</a>
{/if}
{/permissions}
