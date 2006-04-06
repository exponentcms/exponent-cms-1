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
<form method="post">
	<input type="hidden" name="module" value="uiswitchermodule" />
	<input type="hidden" name="action" value="switch" /> 
	<select name="level" onChange="this.form.submit()">
	{foreach from=$levels key=i item=level}
		<option value="{$i}"{if $default_level == $i} selected{/if}>{$level}</option>
	{/foreach}
	</select>
</form>