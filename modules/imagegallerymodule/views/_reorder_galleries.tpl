{*
 *
 * Copyright (c) 2004-2008 OIC Group, Inc.
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
 * $Id: Default.tpl,v 1.4 2005/02/24 20:14:35 Exp $
 *}
 
 <div class="imagegallerymodule reorder_galleries">
	<div class="form_header">
                <h1>{$_TR.reorder_images}</h1>
    </div>

<table>
	<tr>
		<th><strong>{$_TR.gallery_title}</strong></th>
		<th><strong>{$_TR.arrange}</strong></th>
	</tr>
{foreach name=a from=$galleries item=gallery}
{math equation="x+1" x=$gallery->galleryorder assign=nextrank}
{math equation="x-1" x=$gallery->galleryorder assign=prevrank}
<tr class="row {cycle values=odd_row,even_row}">
<td>
<a href="{link action=view_gallery id=$gallery->id}" class="navlink">{$gallery->name}</a>&nbsp;
</td>
<td>
{if $smarty.foreach.a.first == 0}
	<a href="{link action=reorder_galleries a=$gallery->galleryorder b=$prevrank}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}up.gif" title="{$_TR.alt_up}" alt="{$_TR.alt_up}" /></a>
{else}
	<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}up.disabled.gif" title="{$_TR.alt_up_disabled}" alt="{$_TR.alt_up_disabled}" />
{/if}
{if $smarty.foreach.a.last == 0}
	<a href="{link action=reorder_galleries a=$gallery->galleryorder b=$nextrank}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}down.gif" title="{$_TR.alt_down}" alt="{$_TR.alt_down}" /></a>
{else}
	<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}down.disabled.gif" title="{$_TR.alt_down_disabled}" alt="{$_TR.alt_down_disabled}" />
{/if}
</td></tr>
{/foreach}
</table>
</div>