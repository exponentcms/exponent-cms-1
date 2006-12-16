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
{permissions level=$smarty.const.UILEVEL_NORMAL}
<div class="moduletitle administration_moduletitle">{$moduletitle}</div>
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}" title="{$_TR.alt_userperm}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" alt="{$_TR.alt_userperm}" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}" title="{$_TR.alt_groupperm}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" alt="{$_TR.alt_groupperm}" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}
{foreach name=cat from=$menu key=cat item=items}
{assign var=perm_name value=$check_permissions[$cat]}
{if $permissions[$perm_name] == 1}
<div><b>{$cat}</b></div>
<div style="padding-left: 20px">
	{foreach name=links from=$items item=info}
	<a class="mngmntlink administration_mngmntlink" href="{link module=$info.module action=$info.action}">{$info.title}</a><br />
	{/foreach}
</div>
{/if}
{/foreach}
{/permissions}