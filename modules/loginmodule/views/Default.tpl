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
{if $smarty.const.PREVIEW_READONLY == 1}
<i>Logged-in users see this:</i><br />
{/if}
{if $loggedin == true || $smarty.const.PREVIEW_READONLY == 1}
<div class="login_welcom">Welcome, {$displayname}</div>
<a href="{link action=editprofile}">Edit Profile</a><br />
{if $is_group_admin}
<a href="{link action=mygroups}">My Groups</a><br />
{/if}
<a href="{link action=changepass}">Change Password</a><br />
<a href="{link action=logout}">Logout</a><br />
{/if}
{if $smarty.const.PREVIEW_READONLY == 1}
<hr size="1" />
<i>Anonymous visitors see this:</i><br />
{/if}
{if $loggedin == false || $smarty.const.PREVIEW_READONLY == 1}
<form method="post" action="">
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
{/if}