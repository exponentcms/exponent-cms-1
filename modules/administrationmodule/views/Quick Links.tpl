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
{get_user assign=user}
{if $user->id != '' && $user->id != 0} 
<div class="administrationmodule quicklinks yui-panel">
	<div class="hd">
			{gettext str="Administration Quicklinks"}
	</div>
	<div class="bd">
	{if $can_manage_nav == 1}<a class="sitetree" href="{link module=navigationmodule action=manage}">Manage Site Navigation</a>{/if}
	{if $permissions.administrate == 1}
	{permissions level=$smarty.const.UILEVEL_NORMAL}
		<a class="files" href="{$smarty.const.URL_FULL}modules/filemanagermodule/actions/picker.php">Manage Files</a>
		<a class="admin" href="{link module=administrationmodule action=index}">Site Administration</a>
		{*<a id="addmodulelink" class="clicktoaddmodule" href="#">Add Module</a>*}
		<a class="recycle" href="{link module=administrationmodule action=orphanedcontent}">Recycle Bin</a>
	{/permissions}
	{chain module=previewmodule view=Default}		
	{/if}
	</div>



	<div class="hd">
			{$user->username}
	</div>
	<div class="bd">
	{permissions level=$smarty.const.UILEVEL_NORMAL}
	<a class="changepassword" href="{link module=loginmodule action=changepass}">Change Password</a>
	<a class="editprofile" href="{link module=loginmodule action=editprofile}">Edit Profile</a>
	<a class="logout" href="{link module=loginmodule action=logout}">Log Out</a>
	{/permissions}
	</div>
</div>
{/if}
