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
 * $Id: _form_editlisting.tpl,v 1.2 2005/02/19 16:53:36 filetreefrog Exp $
 *}
<div class="module listing form-editlisting">
	<div class="form_header">
	       <h1>{if $is_edit}Edit Listing{else}New Listing{/if}</h1>
	       <p>Enter information for this listing below.</p>
	</div>
	{form action="save_listing"}
		{control type="hidden" name="id" value=$listing->id}
		{control type="text" name="name" value=$listing->name}
		{control type="text" name="url" id="listingURL" value=$listing->url}
		{control type="checkbox" name="opennew" label="Open in new window" checked=$listing->opennew flip=true}
		{control type="textarea" name="summary" id="summary" value=$listing->summary cols=50 rows=10}
		{control type="editor" name="body" id="body" value=$listing->body}
		{control type="file" name="upload" label="Upload Picture" value=$listing->body}
		{control type=buttongroup submit="Save" cancel="Cancel"}
	{/form}
</div>

