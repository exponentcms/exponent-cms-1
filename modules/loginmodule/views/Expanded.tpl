{*
 *
 * Copyright 2004 James Hunt and OIC Group, Inc.
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
Welcome, {$user->firstname} {$user->lastname}<br />
<a href="{link action=editprofile}">Edit Profile</a>&nbsp;|&nbsp;
<a href="{link action=changepass}">Change Password</a>&nbsp;|&nbsp;
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
<input type="text" name="username" id="login_username" size="15" />
<input type="password" name="password" id="login_password" size="15" />
<input type="submit" value="Login" /><br />
{if $smarty.const.SITE_ALLOW_REGISTRATION == 1}
<a href="{link action=createuser}">New Account</a>&nbsp;|&nbsp;
<a href="{link action=resetpass}">Retrieve Password</a>
{/if}
</form>
{/if}