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
 * $Id: Default.tpl,v 1.3 2005/02/19 16:53:35 filetreefrog Exp $
 *}

<div class="listingmodule default">
	{include file="`$smarty.const.BASE`modules/common/views/_permission_icons.tpl"}
	{if $moduletitle}<h1>{$moduletitle}</h1>{/if}
	<div class="listings">
		{foreach name=a from=$listings item=listing}
			<div class="item">
				<a href="{link action=view_listing id=$listing->id}"><img src="{$listing->picpath}"/></a>
				<span class="name">{$listing->name}</span>

				<div class="itemactions">	
				{if $permissions.configure == 1 or $permissions.administrate == 1}
					{if $smarty.foreach.a.first == 0}
						<a href="{link action=rank_switch a=$listing->rank b=$prev id=$listing->id}">			
							<img src="{$smarty.const.ICON_RELATIVE}left.png" title="{$_TR.alt_previous}" alt="{$_TR.alt_previous}" />
						</a>
					{/if}	
					<a href="{link action=edit_listing id=$listing->id}" title="Edit this entry">
						<img src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
					</a>
					<a href="{link action=delete_listing id=$listing->id}" title="Delete this entry">
						<img src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
					</a>	
					{if $smarty.foreach.a.last == 0}
					<a href="{link action=rank_switch a=$next b=$listing->rank id=$listing->id}">
						<img src="{$smarty.const.ICON_RELATIVE}right.png" title="{$_TR.alt_next}" alt="{$_TR.alt_next}" />
					</a>
					{/if}
				{/if}	
				</div>
			</div>
		{foreachelse}
			<div align="center"><i>No listings found.</i></div>
		{/foreach}
	</div>


{if $permissions.administrate == 1}
<div class="moduleactions">
	<a href="{link action=edit_listing}">New Listing</a>
</div>

{/if}
</div>
