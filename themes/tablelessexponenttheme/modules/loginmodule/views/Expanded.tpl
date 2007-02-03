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
{if $loggedin == true || $smarty.const.PREVIEW_READONLY == 1}
<h2>{$_TR.welcome|sprintf:$displayname}</h2>
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
		<input class="loginbutton" type="image" name="imageField" src="{$smarty.const.THEME_RELATIVE}images/button-login.jpg" />
		<input tabindex="2" class="typetext" type="password" name="password" id="login_password" size="15" />
		<input tabindex="1" class="typetext"type="text" name="username" id="login_username" size="15" />
	</form>
{if $smarty.const.SITE_ALLOW_REGISTRATION == 1}
<a href="{link action=createuser}">{$_TR.create_account}</a>&nbsp;|&nbsp;
<a href="{link action=resetpass}">{$_TR.forgot_password}</a>
{/if}
</form>
{/if}</div>