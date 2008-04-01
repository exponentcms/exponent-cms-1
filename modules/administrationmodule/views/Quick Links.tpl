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

<div class="administrationmodule quick-links">
{if $permissions.administrate == 1}
	<h1>{$moduletitle}</h1>
	{permissions level=$smarty.const.UILEVEL_NORMAL}
		<a class="sitetree" href="{link module=navigationmodule action=manage}">Manage Site Navigation</a>
		<a class="files" href="{$smarty.const.URL_FULL}modules/filemanagermodule/actions/picker.php">Manage Files</a>
		<a class="admin" href="{link module=administrationmodule action=index}">Site Administration</a>
		<a id="addmodulelink" class="clicktoaddmodule" href="#">Add Module</a>
		<a class="recycle" href="{link module=administrationmodule action=orphanedcontent}">Recycle Bin</a>
	{/permissions}
{/if}

{get_user assign=user}
{if $user->id != '' && $user->id != 0} 
	{permissions level=$smarty.const.UILEVEL_NORMAL}
	<a class="changepassword" href="{link module=loginmodule action=changepass}">Change Password</a>
	<a class="editprofile" href="{link module=loginmodule action=editprofile}">Edit Profile</a>
	<a class="logout" href="{link module=loginmodule action=logout}">Log Out</a>
	{/permissions}
	{chain module=previewmodule view=Default}
{/if}

{script}
{literal}
	var addmes = YAHOO.util.Dom.getElementsByClassName("addmodule","a");
	var togglelink = YAHOO.util.Dom.get("addmodulelink",true);
	
	
	YAHOO.util.Event.on(togglelink,"click",toggle);
	
	function toggle() {
		if(YAHOO.util.Dom.getStyle(addmes[0],"display") == "none"){
			YAHOO.util.Dom.setStyle(addmes,"display","block");
		} else {
			YAHOO.util.Dom.setStyle(addmes,"display","none");
		}
	}
	
	//alert(addmes);
{/literal}
{/script}

</div>
