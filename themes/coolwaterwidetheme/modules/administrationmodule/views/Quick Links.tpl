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
	{get_user assign=user}
	{if $user->id != '' && $user->id != 0} 
		<div class="administrationmodule quicklinks yui-panel">
			<div class="hd">
				{gettext str=$_TR.quicklinks}
			</div>
			<div class="bd">		
				{permissions level=$smarty.const.UILEVEL_NORMAL}
					{if $can_manage_nav == 1}<a class="sitetree" href="{link module=navigationmodule action=manage}">{$_TR.manage_site}</a>{/if}
						{if $permissions.administrate == 1}
						<a class="files" href="{$smarty.const.URL_FULL}modules/filemanagermodule/actions/picker.php" target="_blank">{$_TR.manage_files}</a>
						<a class="admin" href="{link module=administrationmodule action=index}">{$_TR.site_administration}</a>
						<a class="recycle" href="{link module=administrationmodule action=orphanedcontent}">{$_TR.recycle_bin}</a>
						<a class="announce" href="{link module=administrationmodule action=edit_announcement}">{$_TR.edit_announcement}</a>
					{/if}
				{/permissions}
				{chain module=previewmodule view=Default}		
			</div>
		</div>
	{/if}
</div>
