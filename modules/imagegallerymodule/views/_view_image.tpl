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
 * $Id: _view_image.tpl,v 1.4 2005/03/03 23:43:03 filetreefrog Exp $
 *}
{$image->name}
<br />
{if $permissions.manage == 1}
<a class="mngmntlink imagegallery_mngmntlink" href="{link action=edit_image id=$image->id}">
	<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
</a>
<br />
{/if}
(in <a href="{link action=view_gallery id=$gallery->id}">{$gallery->name}</a>)
{if $image->description != ""}
<hr size="1" />
{$image->description}
<hr size="1" />
{/if}
<br />
<img alt="{$image->alt}" src="{$smarty.const.URL_FULL}{$image->file->directory}/{$image->file->filename}" />
