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
{$template->title}
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.edit == 1}
<a class="mngmntlink htmltemplate_mngmntlink" href="{link action=edit id=$template->id}">
	<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
</a>
{/if}
{if $permissions.delete == 1}
<a class="mngmntlink htmltemplate_mngmntlink" href="{link action=delete id=$template->id}" onclick="return confirm('{$_TR.delete_confirm}');">
	<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
</a>
{/if}
{/permissions}
<div style="border: 1px dashed lightgrey">
{$template->body}
</div>