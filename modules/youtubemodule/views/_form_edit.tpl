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
<div class="form_title">{$_TR.form_title}</div>
<div class="form_header"><p>{$_TR.form_header}</p></div>
{form action="save"}
	{control type=hidden name=id value=$youtube->id}
	{control type="text" name="name" label="Name" value=$youtube->name}
	{control type="text" name="width" label="Width" value=$youtube->width}
	{control type="text" name="height" label="Height" value=$youtube->height}
	{control type="text" name="url" label="URL" value=$youtube->url}
	{control type="editor" name="description" label="description" value=$youtube->description}
	{control type="buttongroup" submit="Save" cancel="Cancel"} 

{/form}
