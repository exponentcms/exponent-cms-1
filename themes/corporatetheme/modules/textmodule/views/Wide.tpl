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
 * $Id$
 *}
<table cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 10px">
	<tr>
		<td><img src="{$smarty.const.THEME_RELATIVE}images/linecorner_topleft.gif"/></td>
		<td width="100%" style="background-image: url({$smarty.const.THEME_RELATIVE}images/lineside_top.gif);"></td>
		<td><img src="{$smarty.const.THEME_RELATIVE}images/linecorner_topright.gif"/></td>
	</tr>
	<tr>
		<td style="background-image: url({$smarty.const.THEME_RELATIVE}images/lineside_left.gif);"></td>
		<td width="100%" height="100%">
			{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
			{if $permissions.administrate == 1}
				<a href="{link action=userperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" /></a>&nbsp;
				<a href="{link action=groupperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" /></a>
			{/if}
			{if $permissions.configure == 1}
				<a href="{link action=configure _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}configure.png" /></a>
			{/if}
			{/permissions}
			{permissions level=$smarty.const.UILEVEL_NORMAL} 
			{if $permissions.edit == 1}
				{if $textitem->approved != 1}
					<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.disabled.png" border="0" title="Editting Disabled - Content In Approval" />&nbsp;
				{else}
					<a class="mngmntlink text_mngmntlink" href="{link action=edit id=$textitem->id}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" border="0" /></a>
				{/if}
			{/if}
			{if $textitem->approved != 1 && ($permissions.approve == 1 || $permissions.manage_approval == 1 || $permissions.edit == 1)}
			<a class="mngmntlink news_mngmntlink" href="{link module=workflow datatype=textitem m=textmodule s=$__loc->src action=summary}">View Approval</a>
			{/if}
			{if $permissions.manage_approval == 1 && ($textitem->id != 0 && $textitem->approved != 0)}
				&nbsp;&nbsp;&nbsp;<a class="mngmntlink text_mngmntlink" href="{link module=workflow datatype=textitem m=textmodule s=$__loc->src action=revisions_view id=$textitem->id}">
					Revisions
				</a>
			{/if}
			{/permissions}
			{if $moduletitle != ""}<div class="moduletitle text_moduletitle">{$moduletitle}</div>{/if}
			<div>
			{if $textitem->approved != 0}
				{$textitem->text}
			{/if}
			</div>
		</td>
		<td style="background-image: url({$smarty.const.THEME_RELATIVE}images/lineside_right.gif);"></td>
	</tr>
	<tr>
		<td><img src="{$smarty.const.THEME_RELATIVE}images/linecorner_bottomleft.gif"/></td>
		<td style="background-image: url({$smarty.const.THEME_RELATIVE}images/lineside_bottom.gif);"></td>
		<td><img src="{$smarty.const.THEME_RELATIVE}images/linecorner_bottomright.gif"/></td>
	</tr>
</table>