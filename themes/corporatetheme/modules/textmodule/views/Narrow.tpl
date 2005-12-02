{*
 * Copyright (c) 2004-2005 OIC Group, Inc.
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
<table cellpadding="0" cellspacing="0" border="0" style="margin-left:10px;margin-right:7px;margin-top:5px;margin-bottom: 10px;">
	<tr>
		<td><img src="{$smarty.const.THEME_RELATIVE}images/corner_topleft.gif" /></td>
		<td style="background-image: url({$smarty.const.THEME_RELATIVE}images/side_top_blank.gif); background-repeat: repeat-x;"></td>
		<td><img src="{$smarty.const.THEME_RELATIVE}images/corner_topright.gif" /></td>
	</tr>
	{if $moduletitle != ""}
	<tr>
		<td style="background-image: url({$smarty.const.THEME_RELATIVE}images/titleside_left.gif); background-repeat: repeat-y;"></td>
		<td style="background-image: url({$smarty.const.THEME_RELATIVE}images/title_bg.gif)">
			<div style="font-weight: bold; font-size: 12pt;">{$moduletitle}</div>
		</td>
		<td style="background-image: url({$smarty.const.THEME_RELATIVE}images/titleside_right.gif); background-repeat: repeat-y;"></td>
	</tr>
	{/if}
	<tr>
		<td style="background-image: url({$smarty.const.THEME_RELATIVE}images/side_left.gif); background-repeat: repeat-y"></td>
		<td width="100%" style="background-image: url({$smarty.const.THEME_RELATIVE}images/middle_bg.gif); text-align: justify">
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
			<div>
			{if $textitem->approved != 0}
				{$textitem->text}
			{/if}
			</div>
		</td>
		<td style="background-image: url({$smarty.const.THEME_RELATIVE}images/side_right.gif); background-repeat: repeat-y"></td>
	</tr>
	<tr>
		<td><img src="{$smarty.const.THEME_RELATIVE}images/corner_bottomleft.gif" /></td>
		<td style="background-image: url({$smarty.const.THEME_RELATIVE}images/side_bottom.gif); background-repeat: repeat-x"></td>
		<td><img src="{$smarty.const.THEME_RELATIVE}images/corner_bottomright.gif" /></td>
	</tr>
</table>
