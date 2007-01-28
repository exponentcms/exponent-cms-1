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
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}" title="{$_TR.alt_userperm}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}" title="{$_TR.alt_groupperm}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
{/if}
{if $permissions.configure == 1}
	<a href="{link action=configure _common=1}" title="{$_TR.alt_configure}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}configure.png" title="{$_TR.alt_configure}" alt="{$_TR.alt_configure}" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}

<div class="moduletitle search_moduletitle">{$moduletitle}</div>
<form method="get" action="">
<input type="hidden" name="module" value="searchmodule" />
<input type="hidden" name="src" value="{$loc->src}" />
<input type="hidden" name="action" value="search" />
<strong>{$_TR.search}: </strong><input type="text" name="search_string" size="20" />

<div class="moduletitle" style="margin-top:15px;">Select areas of the site you would like to search</div>(If you don't select any all will be searched)
<hr size="1" />
<table class="search_modulelist">
	{foreach from=$modules key=module item=module_name name=modules}
		{if $smarty.foreach.modules.index % 3 == 0}	
			</tr><tr>
		{/if}
		<td><input type="checkbox" name="modules[]" value="{$module}">&nbsp;&nbsp;&nbsp;{$module_name}</td>
	{/foreach}
</table>
<input type="submit" value="{$_TR.search}" />
</form>
