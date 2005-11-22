{*
 *
 * Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
 * All Changes as of 6/1/05 Copyright 2005 James Hunt
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
{$template->title}
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.edit == 1}
<a class="mngmntlink htmltemplate_mngmntlink" href="{link action=edit id=$template->id}">
	<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" border="0" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
</a>
{/if}
{if $permissions.delete == 1}
<a class="mngmntlink htmltemplate_mngmntlink" href="{link action=delete id=$template->id}" onClick="return confirm('{$_TR.delete_confirm}');">
	<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" border="0" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
</a>
{/if}
{/permissions}
<div style="border: 1px dashed lightgrey">
{$template->body}
</div>