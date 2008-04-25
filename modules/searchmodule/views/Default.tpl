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

	<form id="form" name="form" class="" method="get" action="{$smarty.const.URL_FULL}" enctype="">
		<input type="hidden" name="module" id="module" value="searchmodule" />
		<input type="hidden" name="src" id="src" value="@random47977a9d212f9" />
		<input type="hidden" name="int" id="int" value="" />
		<input type="hidden" name="action" id="action" value="search" />
		<input name="search_string" class="text" type="text" />
		<input name="search" class="button" value="Search" type="submit" />
	</form>
	
</div>

