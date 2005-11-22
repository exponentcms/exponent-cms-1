{*
 *
 * Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
 * All Changes as of 6/1/05 Copyright 2005 James Hunt
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
<i>{$logged_in_users}:</i><br />
{/if}
{if $loggedin == true || $smarty.const.PREVIEW_READONLY == 1}
{$_TR.welcome|sprintf:$displayname}<br />
<a href="{link action=editprofile}">{$_TR.edit_profile}</a>&nbsp;|&nbsp;
{if $is_group_admin}
<a href="{link action=mygroups}">{$_TR.my_groups}</a>&nbsp;|&nbsp;
{/if}
<a href="{link action=changepass}">{$_TR.change_password}</a>&nbsp;|&nbsp;
<a href="{link action=logout}">{$_TR.logout}</a><br />
{/if}
{if $smarty.const.PREVIEW_READONLY == 1}
<hr size="1" />
<i>{$_TR.anon_users}:</i><br />
{/if}
{if $loggedin == false || $smarty.const.PREVIEW_READONLY == 1}
<form method="post" action="">
<input type="hidden" name="action" value="login" />
<input type="hidden" name="module" value="loginmodule" />
<input type="text" name="username" id="login_username" size="15" />
<input type="password" name="password" id="login_password" size="15" />
<input type="submit" value="{$_TR.login}" /><br />
{if $smarty.const.SITE_ALLOW_REGISTRATION == 1}
<a href="{link action=createuser}">{$_TR.create_account}</a>&nbsp;|&nbsp;
<a href="{link action=resetpass}">{$_TR.forgot_password}</a>
{/if}
</form>
{/if}