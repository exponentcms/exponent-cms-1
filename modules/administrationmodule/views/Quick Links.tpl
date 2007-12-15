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

{if $permissions.administrate == 1}
	<div class="administrationmodule quick-links">
	<h1>{$moduletitle}</h1>
{/if}


{chain module=previewmodule view=Default}

{permissions level=$smarty.const.UILEVEL_NORMAL}
	{if $permissions.administrate == 1}
		<a class="sitetree" href="{link module=navigationmodule action=manage}">Manage Site Navigation</a>
		<a class="files" href="/modules/filemanagermodule/actions/picker.php">Manage Files</a>
		<a class="admin" href="/admin">Site Administration</a>
		{*<a class="trash" href="{link module=containermodule action=view-recycle-bin}">View Recycle Bin</a>
		<a class="clipboard" href="{link module=containermodule action=view-clipboard}">View Clipboard</a>*}
		<a class="logout" href="{link module=loginmodule action=logout}">Log Out</a>
	{/if}
{/permissions}
{if $permissions.administrate == 1}
	</div>
{/if}
