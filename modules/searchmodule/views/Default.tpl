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
<div class="searchmodule searchdefault">
	<div class="permissions">
		{include file="`$smarty.const.BASE`modules/common/views/_permission_icons.tpl"}	
	</div>

	{if $moduletitle!=""}<h1>{$moduletitle}</h1>{/if}
	{form method="get" module="searchmodule" action="search"}
	<input name="search_string" class="textbox" type="text" />
	<input name="search" class="button" value="Search" type="submit" />

	{/form}
	
</div>

