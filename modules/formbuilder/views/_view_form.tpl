{*
 *
 * Copyright 2005 James Hunt and OIC Group, Inc.
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
 
 <div style="border: 2px dashed lightgrey; padding: 1em;">
{$form_html}
</div>
<form method="post" action="?">
<input type="hidden" name="module" value="formbuilder" />
<input type="hidden" name="action" value="edit_control" />
<input type="hidden" name="form_id" value="{$form->id}" />
Add a <select name="control_type" onChange="this.form.submit()">
{foreach from=$types key=value item=caption}
	<option value="{$value}">{$caption}</option>
{/foreach}
</select>
</form>
<a href="{$backlink}">Done</a>