{*
 * Copyright (c) 2004-2005 OIC Group, Inc.
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
 <div id="textmodule">
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>
	<a href="{link action=groupperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
{/if}
{/permissions}
{if $moduletitle != ""}<div class="moduletitle text_moduletitle">{$moduletitle}</div>

{/if}
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.edit == 1}
	{if $textitem->approved != 1}
		<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.disabled.png" border="0" title="{$_TR.alt_noedit}" alt="{$_TR.alt_noedit}" />&nbsp;
	{else}
		<a class="mngmntlink text_mngmntlink" href="{link action=edit id=$textitem->id}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" border="0" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>
	{/if}
{/if}
{if $textitem->approved != 1 && ($permissions.approve == 1 || $permissions.manage_approval == 1 || $permissions.edit == 1)}
<a class="mngmntlink news_mngmntlink" href="{link module=workflow datatype=textitem m=textmodule s=$__loc->src action=summary}">{$_TR.link_viewap}</a>
{/if}
{if $permissions.manage_approval == 1 && ($textitem->id != 0 && $textitem->approved != 0)}
	&nbsp;&nbsp;&nbsp;<a class="mngmntlink text_mngmntlink" href="{link module=workflow datatype=textitem m=textmodule s=$__loc->src action=revisions_view id=$textitem->id}">
		{$_TR.link_manageap}
	</a>
{/if}
{/permissions}
<div>
{if $textitem->approved != 0}
	{$textitem->text}
{/if}
</div>
</div>