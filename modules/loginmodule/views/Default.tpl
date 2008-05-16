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
<div class="loginmodule login-default">

	{if $smarty.const.PREVIEW_READONLY == 1}
		{$_TR.logged_in_users}:
	{/if}
	
	{if $loggedin == true || $smarty.const.PREVIEW_READONLY == 1}
		<h2>{$_TR.welcome|sprintf:$displayname}<h2>
		<a href="{link action=editprofile}">{$_TR.edit_profile}</a>
	
		{if $is_group_admin}
		<a href="{link action=mygroups}">{$_TR.my_groups}</a><br />
		{/if}
	
		<a href="{link action=changepass}">{$_TR.change_password}</a>
		<a href="{link action=logout}">{$_TR.logout}</a>
	{/if}
	
	{if $smarty.const.PREVIEW_READONLY == 1}
		{$_TR.anon_users}:
	{/if}
	
	{if $loggedin == false || $smarty.const.PREVIEW_READONLY == 1}
	<form method="post" action="{$smarty.const.URL_FULL}index.php">
	<input type="hidden" name="action" value="login" />
	<input type="hidden" name="module" value="loginmodule" />
	<span>{gettext str="Username"}</span>
	<input class="text" type="text" name="username" id="login_username" />
	<span>{gettext str="Password"}</span>
	<input class="text" type="password" name="password" id="login_password" />
	<input type="submit" class="button" value="{$_TR.login}" />
	</form>
	{if ($smarty.const.SITE_ALLOW_REGISTRATION == 1 && $smarty.const.MAINTENANCE_MODE != 1)}
	<a href="{link action=createuser}">{$_TR.create_account}</a><br />
	<a href="{link action=resetpass}">{$_TR.forgot_password}</a><br />
	{/if}
	{/if}

</div>


