{*
 *
 * Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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
 * $Id: _view_thread.tpl,v 1.7 2005/04/08 15:45:49 filetreefrog Exp $
 *}
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
<div align="center">
<table width="85%" cellpadding="0" cellspacing="1" border="0">
	<tr>
		<td class="header bb_header">Rank Title</td>
		<td class="header bb_header">Minimum Posts</td>
		<td class="header bb_header">Set as Special</td>
		<td class="header bb_header">Edit</td>
		<td class="header bb_header">Delete</td>
	</tr>
{foreach from=$ranks item=rank}
	<tr style="border-bottom: 1px solid black; ">
		<td class="bb_boardposts" style="border-bottom: 1px solid black;">{$rank->title}</td>
		<td class="bb_boardposts" style="border-bottom: 1px solid black;">{$rank->minimum_posts}</td>
		<td class="bb_boardposts" style="border-bottom: 1px solid black;">{if $rank->is_special == 0}No{else}Yes{/if}</td>
		<td class="bb_boardposts" style="border-bottom: 1px solid black;">
			<a href="{link module=bbmodule action=edit_rank id=$rank->id}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>
		</td>
		<td class="bb_boardposts" style="border-bottom: 1px solid black;">
			<a href="{link module=bbmodule action=delete_rank id=$rank->id}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" /></a>
		</td>
	</tr>
{/foreach}
<tr><td align="left"><br /><br /><a href="{link module=bbmodule action=edit_rank}">Create a new rank</a></td></tr>
</table>
</div>
{/if}
{/permissions}
