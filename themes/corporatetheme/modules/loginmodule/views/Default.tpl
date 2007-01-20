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
{if $smarty.const.PREVIEW_READONLY == 1}
<i>Logged-in users see this:</i><br />
{/if}
{if $loggedin == true || $smarty.const.PREVIEW_READONLY == 1}
<table cellpadding="0" cellspacing="0" border="0" style="margin-left:10px;margin-right:7px;margin-top:5px;margin-bottom: 10px;">
	<tr>
		<td><img src="{$smarty.const.THEME_RELATIVE}images/corner_topleft.gif" alt="" /></td>
		<td style="background-image: url({$smarty.const.THEME_RELATIVE}images/side_top_blank.gif); background-repeat: repeat-x;"></td>
		<td><img src="{$smarty.const.THEME_RELATIVE}images/corner_topright.gif" alt="" /></td>
	</tr>
	<tr>
		<td style="background-image: url({$smarty.const.THEME_RELATIVE}images/titleside_left.gif); background-repeat: repeat-y;"></td>
		<td style="background-image: url({$smarty.const.THEME_RELATIVE}images/title_bg.gif)">
			<div style="font-weight: bold; font-size: 12pt;">User Menu</div>
		</td>
		<td style="background-image: url({$smarty.const.THEME_RELATIVE}images/titleside_right.gif); background-repeat: repeat-y;"></td>
	</tr>
	<tr>
		<td style="background-image: url({$smarty.const.THEME_RELATIVE}images/side_left.gif); background-repeat: repeat-y"></td>
		<td width="100%" style="background-image: url({$smarty.const.THEME_RELATIVE}images/middle_bg.gif); text-align: justify">

			<div class="login_welcom">Welcome, {$user->firstname} {$user->lastname}</div>
			<a href="{link action=editprofile}">Edit Profile</a><br />
			<a href="{link action=changepass}">Change Password</a><br />
			<a href="{link action=logout}">Logout</a><br />

		</td>
		<td style="background-image: url({$smarty.const.THEME_RELATIVE}images/side_right.gif); background-repeat: repeat-y"></td>
	</tr>
	<tr>
		<td><img src="{$smarty.const.THEME_RELATIVE}images/corner_bottomleft.gif" alt=""  /></td>
		<td style="background-image: url({$smarty.const.THEME_RELATIVE}images/side_bottom.gif); background-repeat: repeat-x"></td>
		<td><img src="{$smarty.const.THEME_RELATIVE}images/corner_bottomright.gif" alt="" /></td>
	</tr>
</table>
{/if}
{if $smarty.const.PREVIEW_READONLY == 1}
<hr size="1" />
<i>Anonymous visitors see this:</i><br />
{/if}
{if $loggedin == false || $smarty.const.PREVIEW_READONLY == 1}
<table cellpadding="0" cellspacing="0" border="0" style="margin-left:10px;margin-right:7px;margin-top:5px;margin-bottom: 10px;">
	<tr>
		<td><img src="{$smarty.const.THEME_RELATIVE}images/corner_topleft.gif" alt="" /></td>
		<td style="background-image: url({$smarty.const.THEME_RELATIVE}images/side_top_blank.gif); background-repeat: repeat-x;"></td>
		<td><img src="{$smarty.const.THEME_RELATIVE}images/corner_topright.gif" alt="" /></td>
	</tr>
	<tr>
		<td style="background-image: url({$smarty.const.THEME_RELATIVE}images/titleside_left.gif); background-repeat: repeat-y;"></td>
		<td style="background-image: url({$smarty.const.THEME_RELATIVE}images/title_bg.gif)">
			<div style="font-weight: bold; font-size: 12pt;">User Menu</div>
		</td>
		<td style="background-image: url({$smarty.const.THEME_RELATIVE}images/titleside_right.gif); background-repeat: repeat-y;"></td>
	</tr>
	<tr>
		<td style="background-image: url({$smarty.const.THEME_RELATIVE}images/side_left.gif); background-repeat: repeat-y"></td>
		<td width="100%" style="background-image: url({$smarty.const.THEME_RELATIVE}images/middle_bg.gif); text-align: justify">
		
			<form method="post" action="?">
				<input type="hidden" name="action" value="login" />
				<input type="hidden" name="module" value="loginmodule" />
				<input type="text" name="username" id="login_username" size="15" /><br />
				<input type="password" name="password" id="login_password" size="15" /><br />
				<input type="submit" value="Login" /><br />
			</form>
			{if $smarty.const.SITE_ALLOW_REGISTRATION == 1}
			<a href="{link action=createuser}">Create</a> an account<br />
			<a href="{link action=resetpass}">Forgot Your Password?</a><br />
			{/if}
			
		</td>
		<td style="background-image: url({$smarty.const.THEME_RELATIVE}images/side_right.gif); background-repeat: repeat-y"></td>
	</tr>
	<tr>
		<td><img src="{$smarty.const.THEME_RELATIVE}images/corner_bottomleft.gif"  alt="" /></td>
		<td style="background-image: url({$smarty.const.THEME_RELATIVE}images/side_bottom.gif); background-repeat: repeat-x"></td>
		<td><img src="{$smarty.const.THEME_RELATIVE}images/corner_bottomright.gif"  alt="" /></td>
	</tr>
</table>
{/if}
