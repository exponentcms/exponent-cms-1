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
 * $Id: _form_editgallery.tpl,v 1.3 2005/02/19 16:40:42 filetreefrog Exp $
 *}
<div class="imagegallery edit">
	<div class="form_header">
		<h1>{if $is_edit}Edit Gallery Properties{else}New Gallery{/if}</h1>
		<p>Enter the name and description of the gallery below.</p>
	</div>
	{form name="gallery" module="imagegallerymodule" int=2 action="save_gallery"}
	{control type="hidden" name="id" value=$gallery->id}
	{control type="textbox" name="name" value=$gallery->name label="Name"}
	{control type="editor" name="description" value=$gallery->description label="Description"}
	{control type="textbox" name="box_size" value=$gallery->box_size label="Thumbnail Size (pixels)"}
	{control type="textbox" name="pop_size" value=$gallery->pop_size label="Enlarged Size (pixels)"}
	{control type="textbox" name="perrow" value=$gallery->perrow label="Images per row"}
	{control type="textbox" name="perpage" value=$gallery->perpage label="Images per page"}
	{control type="buttongroup" submit="Save Gallery Settings"}
	{/form}	

</div>