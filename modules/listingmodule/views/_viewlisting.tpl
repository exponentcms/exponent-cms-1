{*
 *
 * Copyright (c) 2004-2011 OIC Group, Inc.
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
 * $Id: _viewlisting.tpl,v 1.3 2005/02/19 16:53:36 filetreefrog Exp $
 *}

<div class="listingmodule viewlisting">
	{printer_friendly_link class="printer-friendly-link" text=$_TR.printer_friendly}
	{br}
	{if $moduletitle}<h2>{$moduletitle}</h2>{/if}
	<h3>
		{$listing->name}
		{permissions level=$smarty.const.UILEVEL_PERMISSIONS}							
			{if $permissions.configure == 1 or $permissions.administrate == 1}
				<a href="{link action=edit_listing id=$listing->id}" title="{$_TR.alt_edit}">
					<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
				</a>
				<a href="{link action=delete_listing id=$listing->id}" title="{$_TR.alt_delete}" onclick="return confirm('{$_TR.delete_confirm}');">
					<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
				</a>	
			{/if}							
			{if $permissions.approve == 1 || $listing->permissions.approve == true}
				<a class="mngmntlink listing_mngmntlink" href="{link module=workflow datatype=listing m=listingmodule s=$__loc->src action=revisions_view id=$listing->id}" title="{$_TR.alt_revisions}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}revisions.png" title="{$_TR.alt_revisions}" alt="{$_TR.alt_revisions}"/></a>
			{/if}
		{/permissions}	
	</h3>
	<div class="bodycopy">
		{if $listing->file_id}
			<img class="listingimage" src="{$smarty.const.PATH_RELATIVE}thumb.php?id={$listing->file_id}&constraint=1&width=225&height=275" title="{$listing->summary} alt="{$listing->name}" />
		{/if}
		<p>{$listing->body}</p>
	</div>
</div>
