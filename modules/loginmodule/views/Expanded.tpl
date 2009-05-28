{*
 * Copyright (c) 2004-2006 OIC Group, Inc.
 * Written and Designed by Phillip Ball
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

<div class="loginmodule expanded">
{if $smarty.const.PREVIEW_READONLY == 1}
	<em>{$logged_in_users}:</em><br />
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
	<hr />
	<em>{$_TR.anon_users}:</em><br />
{/if}
{if $loggedin == false || $smarty.const.PREVIEW_READONLY == 1}
	<form id="loginexpanded" method="post" action="{$smarty.const.URL_FULL}index.php">
		<div>
			<input type="hidden" name="action" value="login" />
			<input type="hidden" name="module" value="loginmodule" />
			<label for="login_username">{$_TR.name}</label>
			<input type="text" class="text" name="username" id="login_username" size="15" maxlength="30" value="{$_TR.enter_name}" onfocus="if (this.value==this.defaultValue) this.value=''; else this.select()" onblur="if (!this.value) this.value=this.defaultValue" />
			<label for="login_password">{$_TR.passw}</label>
			<input type="password" class="text" name="password" id="login_password" size="15" maxlength="32" value="{$_TR.enter_pw}" onfocus="if (this.value==this.defaultValue) this.value=''; else this.select()" onblur="if (!this.value) this.value=this.defaultValue"/>		
			<input type="submit" class="button" value="{$_TR.login}" /><br />
			{if $smarty.const.SITE_ALLOW_REGISTRATION == 1}
				<a href="{link action=createuser}">{$_TR.create_account}</a>&nbsp;|&nbsp;
				<a href="{link action=resetpass}">{$_TR.forgot_password}</a>
			{/if}
		</div>
	</form>
{/if}
</div>