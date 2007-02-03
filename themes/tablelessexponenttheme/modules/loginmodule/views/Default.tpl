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
<div class="login">
{if $smarty.const.PREVIEW_READONLY == 1}
<i>{$_TR.logged_in_users}:</i><br />
{/if}
{if $loggedin == true || $smarty.const.PREVIEW_READONLY == 1}
<div class="login_welcom">{$_TR.welcome|sprintf:$displayname}</div>
<a href="{link action=editprofile}">{$_TR.edit_profile}</a><br />
{if $is_group_admin}
<a href="{link action=mygroups}">{$_TR.my_groups}</a><br />
{/if}
<a href="{link action=changepass}">{$_TR.change_password}</a><br />
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
		<input class="loginbutton" type="image" name="imageField" src="images/button-login.jpg" /><input class="typetext"type="text" name="username" id="login_username" size="15" /><input class="typetext" type="password" name="password" id="login_password" size="15" />
	</form>
	<a href="?action=createuser&amp;module=loginmodule">Create Account</a>&nbsp;|&nbsp;
	<a href="p?action=resetpass&amp;module=loginmodule">Retrieve Password</a>

{if $smarty.const.SITE_ALLOW_REGISTRATION == 1}
<a href="{link action=createuser}">{$_TR.create_account}</a><br />
<a href="{link action=resetpass}">{$_TR.forgot_password}</a><br />
{/if}
{/if}
</div>
